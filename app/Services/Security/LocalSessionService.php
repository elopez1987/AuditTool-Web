<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Models\Security\LocalTokenMetadata;
use App\Repositories\Security\LocalLoginRepository;
use App\Repositories\Security\LocalSessionRepository;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;

final class LocalSessionService
{
    public function __construct(
        private readonly LocalSessionRepository $sessions,
        private readonly LocalLoginRepository $users,
        private readonly DatabaseManager $database,
        private readonly Repository $config,
        private readonly Encrypter $encrypter,
        private readonly RateLimiter $limiter,
    ) {}

    /**
     * Return rejections so the caller commits security revocations before throwing.
     *
     * @param  array{client_id: string, user_id: string, access_token_id: string, refresh_token_id: string, expire_time: int}  $data
     */
    public function prepareRefresh(array $data): LocalTokenMetadata|LocalAuthException
    {
        $user = $this->users->findById($data['user_id'], lock: true);
        $token = $this->sessions->accessToken($data['access_token_id'], $this->clientId(), (int) $data['user_id']);
        $refresh = $this->sessions->refreshToken($data['refresh_token_id']);
        $metadata = $this->sessions->metadata($data['access_token_id']);

        if ($token === null || $refresh === null || $refresh->access_token_id !== $token->id
            || $metadata === null || (int) $metadata->user_id !== (int) $data['user_id']
            || ! $metadata->session_family_id) {
            return $this->reject('invalid_session', $metadata);
        }
        if ($refresh->consumed_at !== null) {
            $this->sessions->revokeFamily((int) $data['user_id'], $this->clientId(), $metadata->session_family_id);

            return $this->reject('refresh_reuse', $metadata);
        }
        if ($user === null || $user->statusid !== 'E' || $user->trashed()
            || ! $this->credentialsMatch($user, $metadata)) {
            $this->sessions->revokeUser((int) $data['user_id'], $this->clientId());

            return $this->reject('account_invalidated', $metadata);
        }
        if ($refresh->revoked || $token->revoked || $metadata->is_revoked
            || $data['expire_time'] <= now('UTC')->timestamp || $refresh->expires_at === null
            || $refresh->expires_at->isPast() || $metadata->session_expires_at === null
            || $metadata->session_expires_at->isPast() || ! $this->deviceExists($metadata)) {
            $this->sessions->revokeFamily((int) $user->id, $this->clientId(), $metadata->session_family_id);

            return $this->reject('invalid_session', $metadata);
        }

        $key = 'local-auth:refresh:family:'.$metadata->session_family_id;
        if ($this->limiter->tooManyAttempts($key, (int) $this->config->get('local_auth.limits.refresh_family', 6))) {
            return new LocalAuthException('Demasiadas solicitudes. Inténtalo más tarde.', 429,
                'rate_limited', ['Retry-After' => (string) $this->limiter->availableIn($key)]);
        }
        $this->limiter->hit($key, 60);

        return $metadata;
    }

    /**
     * @param  array{client_id: string, user_id: string, access_token_id: string, refresh_token_id: string, expire_time: int}  $data
     * @param  array{ip_address: string}  $context
     */
    public function record(
        LocalAppUser $user,
        array $data,
        array $context,
        ?LocalTokenMetadata $previous = null,
        ?int $deviceId = null,
    ): LocalTokenMetadata {
        $token = $this->sessions->accessToken($data['access_token_id'], $this->clientId(), (int) $user->id);
        if ($token === null) {
            throw new LocalAuthException('No se pudo registrar la sesión.', 503);
        }

        return $this->sessions->createMetadata([
            'token_id' => $token->id,
            'user_id' => $user->id,
            'device_id' => $previous?->device_id ?? $deviceId,
            'device_fingerprint' => $previous?->device_fingerprint ?? hash('sha256', (string) $deviceId),
            'ip_address' => $context['ip_address'],
            'user_agent' => null,
            'expires_at' => $token->expires_at->utc(),
            'last_used_at' => now('UTC'),
            'is_revoked' => false,
            'session_family_id' => $previous?->session_family_id ?? (string) Str::uuid(),
            'session_started_at' => $previous?->session_started_at ?? now('UTC'),
            'session_expires_at' => $previous?->session_expires_at
                ?? now('UTC')->addDays(max(1, (int) $this->config->get('local_auth.absolute_session_days', 30))),
            'credential_fingerprint' => $this->credentialFingerprint($user),
        ]);
    }

    public function validateAccess(LocalAppUser $user, string $tokenId): LocalTokenMetadata
    {
        $metadata = $this->sessions->metadata($tokenId);
        if ($metadata === null || ! $metadata->session_family_id || (int) $metadata->user_id !== (int) $user->id) {
            throw $this->reject();
        }
        if (! $this->credentialsMatch($user, $metadata)) {
            $this->revokeUser((int) $user->id);
            throw $this->reject('account_invalidated');
        }
        if ($metadata->is_revoked || $metadata->session_expires_at === null
            || $metadata->session_expires_at->isPast() || ! $this->deviceExists($metadata)) {
            $this->revokeSession($user, $tokenId);
            throw $this->reject();
        }
        $this->sessions->touch($tokenId);

        return $metadata;
    }

    public function revokeUser(int $userId): void
    {
        if ($this->clientId() === '') {
            return;
        }
        $this->database->connection()->transaction(function () use ($userId): void {
            $this->users->findById($userId, lock: true);
            $this->sessions->revokeUser($userId, $this->clientId());
        });
    }

    public function revokeSession(LocalAppUser $user, string $tokenId): void
    {
        $this->database->connection()->transaction(function () use ($user, $tokenId): void {
            $this->users->findById($user->id, lock: true);
            $metadata = $this->sessions->metadata($tokenId);
            if ($metadata?->session_family_id && (int) $metadata->user_id === (int) $user->id) {
                $this->sessions->revokeFamily((int) $user->id, $this->clientId(), $metadata->session_family_id);
            } else {
                $this->sessions->revokeToken($tokenId, (int) $user->id, $this->clientId());
            }
        });
    }

    public function revokeDevice(int $userId, int $deviceId): void
    {
        if ($this->clientId() === '') {
            return;
        }
        $this->database->connection()->transaction(function () use ($userId, $deviceId): void {
            $this->users->findById($userId, lock: true);
            $this->sessions->revokeDevice($userId, $this->clientId(), $deviceId);
        });
    }

    private function credentialsMatch(LocalAppUser $user, LocalTokenMetadata $metadata): bool
    {
        return is_string($metadata->credential_fingerprint)
            && hash_equals($metadata->credential_fingerprint, $this->credentialFingerprint($user))
            && ($user->password_changed_at === null
                || $metadata->session_started_at?->greaterThanOrEqualTo($user->password_changed_at));
    }

    private function credentialFingerprint(LocalAppUser $user): string
    {
        return hash_hmac('sha256', (string) $user->password, $this->encrypter->getKey());
    }

    public function purgeExpired(): int
    {
        if ($this->clientId() === '') {
            throw new LocalAuthException('El cliente local no está configurado.', 503);
        }

        return $this->database->connection()->transaction(
            fn (): int => $this->sessions->purgeExpired($this->clientId(), now('UTC')->subDays(7))
        );
    }

    private function deviceExists(LocalTokenMetadata $metadata): bool
    {
        return $metadata->device_id === null
            || $this->users->deviceExists((int) $metadata->user_id, (int) $metadata->device_id);
    }

    private function clientId(): string
    {
        return (string) $this->config->get('local_auth.client_id');
    }

    private function reject(string $reason = 'invalid_session', ?LocalTokenMetadata $metadata = null): LocalAuthException
    {
        return new LocalAuthException('Se requiere iniciar sesión nuevamente.', 401, $reason,
            userId: $metadata === null ? null : (int) $metadata->user_id,
            familyId: $metadata?->session_family_id);
    }
}
