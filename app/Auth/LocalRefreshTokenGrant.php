<?php

declare(strict_types=1);

namespace App\Auth;

use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Services\Security\LocalAccountService;
use DateInterval;
use DateTimeImmutable;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LocalRefreshTokenGrant extends RefreshTokenGrant
{
    public ?LocalAppUser $user = null;

    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokens,
        private readonly LocalAccountService $accounts,
        private readonly DateTimeImmutable $sessionExpiresAt,
    ) {
        parent::__construct($refreshTokens);
    }

    /**
     * Cap the absolute expiry before persistence and JWT signing.
     *
     * @param  array<ScopeEntityInterface>  $scopes
     */
    protected function issueAccessToken(
        DateInterval $accessTokenTTL,
        ClientEntityInterface $client,
        ?string $userIdentifier,
        array $scopes = [],
    ): AccessTokenEntityInterface {
        $token = $this->accessTokenRepository->getNewToken($client, $scopes, $userIdentifier);
        $token->setExpiryDateTime($this->cappedExpiry($accessTokenTTL));
        $token->setPrivateKey($this->privateKey);

        for ($attempt = 0; $attempt < self::MAX_RANDOM_TOKEN_GENERATION_ATTEMPTS; $attempt++) {
            $token->setIdentifier($this->generateUniqueIdentifier());
            try {
                $this->accessTokenRepository->persistNewAccessToken($token);

                return $token;
            } catch (UniqueTokenIdentifierConstraintViolationException $exception) {
                if ($attempt === self::MAX_RANDOM_TOKEN_GENERATION_ATTEMPTS - 1) {
                    throw $exception;
                }
            }
        }

        return $token;
    }

    protected function issueRefreshToken(AccessTokenEntityInterface $accessToken): ?RefreshTokenEntityInterface
    {
        if (! $this->supportsGrantType($accessToken->getClient(), 'refresh_token')) {
            return null;
        }

        $token = $this->refreshTokenRepository->getNewRefreshToken();
        if ($token === null) {
            return null;
        }
        $token->setExpiryDateTime($this->cappedExpiry($this->refreshTokenTTL));
        $token->setAccessToken($accessToken);

        for ($attempt = 0; $attempt < self::MAX_RANDOM_TOKEN_GENERATION_ATTEMPTS; $attempt++) {
            $token->setIdentifier($this->generateUniqueIdentifier());
            try {
                $this->refreshTokenRepository->persistNewRefreshToken($token);

                return $token;
            } catch (UniqueTokenIdentifierConstraintViolationException $exception) {
                if ($attempt === self::MAX_RANDOM_TOKEN_GENERATION_ATTEMPTS - 1) {
                    throw $exception;
                }
            }
        }

        return $token;
    }

    private function cappedExpiry(DateInterval $lifetime): DateTimeImmutable
    {
        $now = new DateTimeImmutable;
        if ($now >= $this->sessionExpiresAt) {
            throw new LocalAuthException('Se requiere iniciar sesión nuevamente.');
        }

        $expiresAt = $now->add($lifetime);

        return $expiresAt > $this->sessionExpiresAt ? $this->sessionExpiresAt : $expiresAt;
    }

    /**
     * Recheck revocation after acquiring the user lock to serialize refresh reuse.
     *
     * @return array<string, mixed>
     */
    protected function validateOldRefreshToken(ServerRequestInterface $request, string $clientId): array
    {
        $data = parent::validateOldRefreshToken($request, $clientId);
        $this->user = $this->accounts->fromId($data['user_id']);

        return parent::validateOldRefreshToken($request, $clientId);
    }
}
