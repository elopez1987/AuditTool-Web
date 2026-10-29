<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use App\Models\Security\LocalTokenMetadata;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

final class LocalSessionRepository
{
    public function accessToken(string $id, string $clientId, int $userId): ?Token
    {
        return Token::query()->whereKey($id)->where('client_id', $clientId)->where('user_id', $userId)->first();
    }

    public function refreshToken(string $id): ?RefreshToken
    {
        return RefreshToken::query()->whereKey($id)->lockForUpdate()->first();
    }

    public function metadata(string $tokenId): ?LocalTokenMetadata
    {
        return LocalTokenMetadata::query()->where('token_id', $tokenId)->first();
    }

    /** @param array<string, mixed> $attributes */
    public function createMetadata(array $attributes): LocalTokenMetadata
    {
        return LocalTokenMetadata::query()->create($attributes);
    }

    public function markConsumed(string $refreshId, string $replacementId): void
    {
        RefreshToken::query()->whereKey($refreshId)->update([
            'consumed_at' => now('UTC'),
            'replaced_by_id' => $replacementId,
        ]);
    }

    public function markRotated(string $tokenId): void
    {
        LocalTokenMetadata::query()->where('token_id', $tokenId)->update([
            'is_revoked' => true,
            'revoked_at' => now('UTC'),
        ]);
    }

    public function touch(string $tokenId): void
    {
        LocalTokenMetadata::query()->where('token_id', $tokenId)
            ->where('last_used_at', '<', now('UTC')->subMinute())
            ->update(['last_used_at' => now('UTC')]);
    }

    public function revokeUser(int $userId, string $clientId): void
    {
        $this->revoke(Token::query()->where('user_id', $userId)->where('client_id', $clientId));
    }

    public function revokeFamily(int $userId, string $clientId, string $familyId): void
    {
        $this->revoke(Token::query()->where('user_id', $userId)->where('client_id', $clientId)
            ->whereIn('id', LocalTokenMetadata::query()
                ->where('user_id', $userId)->where('session_family_id', $familyId)->select('token_id')));
    }

    public function revokeDevice(int $userId, string $clientId, int $deviceId): void
    {
        $this->revoke(Token::query()->where('user_id', $userId)->where('client_id', $clientId)
            ->whereIn('id', LocalTokenMetadata::query()
                ->where('user_id', $userId)->where('device_id', $deviceId)->select('token_id')));
    }

    public function revokeToken(string $tokenId, int $userId, string $clientId): void
    {
        $this->revoke(Token::query()->whereKey($tokenId)->where('user_id', $userId)->where('client_id', $clientId));
    }

    public function purgeExpired(string $clientId, DateTimeInterface $before): int
    {
        $ids = Token::query()->where('client_id', $clientId)
            ->whereIn('id', LocalTokenMetadata::query()->whereNotNull('session_family_id')
                ->where('session_expires_at', '<', $before)->select('token_id'))
            ->limit(500)->pluck('id')->all();
        if ($ids === []) {
            return 0;
        }
        RefreshToken::query()->whereIn('access_token_id', $ids)->delete();
        LocalTokenMetadata::query()->whereIn('token_id', $ids)->delete();

        return Token::query()->whereIn('id', $ids)->delete();
    }

    /** @param Builder<Token> $tokens */
    private function revoke(Builder $tokens): void
    {
        // Do not filter revoked access tokens: their native refresh tokens must also be invalidated.
        $ids = (clone $tokens)->select('id');
        RefreshToken::query()->whereIn('access_token_id', clone $ids)->update(['revoked' => true]);
        LocalTokenMetadata::query()->whereIn('token_id', clone $ids)->update([
            'is_revoked' => true,
            'revoked_at' => now('UTC'),
        ]);
        $tokens->update(['revoked' => true]);
    }
}
