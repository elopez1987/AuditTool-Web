<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use InvalidArgumentException;
use JsonException;
use Laravel\Passport\Passport;
use League\OAuth2\Server\CryptTrait;

final class LocalRefreshTokenDecoder
{
    use CryptTrait;

    public function __construct(Encrypter $encrypter, private readonly Repository $config)
    {
        $this->setEncryptionKey(Passport::tokenEncryptionKey($encrypter));
    }

    /** @return array{client_id: string, user_id: string, access_token_id: string, refresh_token_id: string, expire_time: int} */
    public function decode(#[\SensitiveParameter] string $token): array
    {
        try {
            $data = json_decode($this->decrypt($token), true, 16, JSON_THROW_ON_ERROR);
        } catch (InvalidArgumentException|JsonException) {
            throw new LocalAuthException('Token de renovación inválido.');
        }

        if (! is_array($data)
            || ! is_string($data['client_id'] ?? null)
            || $data['client_id'] !== (string) $this->config->get('local_auth.client_id')
            || ! is_string($data['user_id'] ?? null)
            || ! ctype_digit($data['user_id']) || strlen($data['user_id']) > 19
            || ! is_string($data['access_token_id'] ?? null)
            || ! preg_match('/\A[a-f0-9]{80}\z/D', $data['access_token_id'])
            || ! is_string($data['refresh_token_id'] ?? null)
            || ! preg_match('/\A[a-f0-9]{80}\z/D', $data['refresh_token_id'])
            || ! is_int($data['expire_time'] ?? null)) {
            throw new LocalAuthException('Token de renovación inválido.');
        }

        return $data;
    }
}
