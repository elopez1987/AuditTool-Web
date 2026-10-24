<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\Security\LocalAppUser;
use App\Services\Security\LocalAccountService;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LocalRefreshTokenGrant extends RefreshTokenGrant
{
    public ?LocalAppUser $user = null;

    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokens,
        private readonly LocalAccountService $accounts,
    ) {
        parent::__construct($refreshTokens);
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
