<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Repositories\Security\LocalLoginRepository;
use App\Repositories\Security\LocalSessionRepository;
use Illuminate\Database\DatabaseManager;

final class LocalLoginService
{
    public function __construct(
        private readonly LocalPassportTokenService $tokens,
        private readonly LocalLoginRepository $users,
        private readonly DatabaseManager $database,
        private readonly LocalSessionService $sessions,
        private readonly LocalRefreshTokenDecoder $decoder,
        private readonly LocalSessionRepository $sessionRecords,
    ) {}

    /**
     * @param  array{username: string, password: string, serialnumber?: ?string}  $data
     * @param  array{ip_address: string}  $context
     * @return array<string, mixed>
     */
    public function login(#[\SensitiveParameter] array $data, array $context): array
    {
        return $this->database->connection()->transaction(function () use ($data, $context): array {
            $issued = $this->tokens->login($data['username'], $data['password']);
            $profile = $this->profile($issued['user'], $data['serialnumber'] ?? null);
            $session = $this->sessions->record($issued['user'], $this->decoder->decode($issued['auth']['refresh_token']),
                $context, deviceId: $profile['device']?->id);

            return $profile + ['auth' => $issued['auth'], 'session_family_id' => $session->session_family_id];
        });
    }

    /**
     * @param  array{ip_address: string}  $context
     * @return array<string, mixed>
     */
    public function refresh(#[\SensitiveParameter] string $refreshToken, array $context): array
    {
        $data = $this->decoder->decode($refreshToken);
        $result = $this->database->connection()->transaction(function () use ($refreshToken, $context, $data): array|LocalAuthException {
            $previous = $this->sessions->prepareRefresh($data);
            if ($previous instanceof LocalAuthException) {
                return $previous;
            }
            $issued = $this->tokens->refresh($refreshToken, $previous->session_expires_at);
            $next = $this->decoder->decode($issued['auth']['refresh_token']);
            $this->sessions->record($issued['user'], $next, $context, $previous);
            $this->sessionRecords->markConsumed($data['refresh_token_id'], $next['refresh_token_id']);
            $this->sessionRecords->markRotated($data['access_token_id']);

            return $this->profile($issued['user']) + [
                'auth' => $issued['auth'], 'session_family_id' => $previous->session_family_id,
            ];
        });

        if ($result instanceof LocalAuthException) {
            throw $result;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function profile(LocalAppUser $user, ?string $serialNumber = null): array
    {
        $access = $this->users->defaultAccess((string) $user->username);

        if ($access === null) {
            throw new LocalAuthException('El usuario no tiene un centro de distribución activo asignado.', 403);
        }

        $device = $serialNumber === null ? null : $this->users->registerDevice((int) $user->id, $serialNumber);

        return [
            'user' => $this->users->loadPermissions($user),
            'center' => $access->center,
            'warehouses' => $this->users->warehouses((string) $user->username, $access->centerid),
            'device' => $device,
        ];
    }
}
