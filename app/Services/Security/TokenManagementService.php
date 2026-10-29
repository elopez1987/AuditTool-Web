<?php

namespace App\Services\Security;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TokenManagementService
{
    /**
     * Create a new access token with enhanced security
     */
    public function createSecureToken(User $user, string $deviceFingerprint, array $additionalClaims = []): array
    {
        // Revoke existing tokens for the same device (optional single-session policy)
        $this->revokeTokensForDevice($user, $deviceFingerprint);

        // Create the access token
        $accessToken = $user->createToken('YMS-API', ['*'], Carbon::now()->addHours(24));
        $token = $accessToken->token;

        // Store token metadata
        $tokenData = [
            'user_id' => $user->id,
            'token_id' => $token->id,
            'device_fingerprint' => $deviceFingerprint,
            'expires_at' => $token->expires_at,
            'created_at' => Carbon::now(),
            'last_used_at' => Carbon::now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_revoked' => false,
            'additional_claims' => json_encode($additionalClaims),
        ];

        $this->storeTokenMetadata($token->id, $tokenData);

        // Create refresh token
        $refreshToken = $this->createRefreshToken($user, $token->id, $deviceFingerprint);

        return [
            'access_token' => $accessToken->accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 86400, // 24 hours
            'expires_at' => $token->expires_at->toISOString(),
            'scope' => implode(' ', $accessToken->token->scopes),
        ];
    }

    /**
     * Create a refresh token
     */
    private function createRefreshToken(User $user, string $tokenId, string $deviceFingerprint): string
    {
        $refreshTokenData = [
            'token' => bin2hex(random_bytes(64)),
            'user_id' => $user->id,
            'access_token_id' => $tokenId,
            'device_fingerprint' => $deviceFingerprint,
            'expires_at' => Carbon::now()->addDays(30), // 30 days
            'created_at' => Carbon::now(),
            'is_used' => false,
        ];

        DB::table('refresh_tokens')->insert($refreshTokenData);

        return $refreshTokenData['token'];
    }

    /**
     * Refresh access token using refresh token
     */
    public function refreshToken(string $refreshToken, string $deviceFingerprint): ?array
    {
        $refreshTokenData = DB::table('refresh_tokens')
            ->where('token', $refreshToken)
            ->where('device_fingerprint', $deviceFingerprint)
            ->where('expires_at', '>', Carbon::now())
            ->where('is_used', false)
            ->first();

        if (! $refreshTokenData) {
            Log::channel('security')->warning('Invalid refresh token used', [
                'refresh_token' => substr($refreshToken, 0, 10).'...',
                'device_fingerprint' => substr($deviceFingerprint, 0, 12).'...',
                'ip' => request()->ip(),
            ]);

            return null;
        }

        $user = User::find($refreshTokenData->user_id);
        if (! $user || $user->statusid === 'D') {
            return null;
        }

        // Mark refresh token as used
        DB::table('refresh_tokens')
            ->where('id', $refreshTokenData->id)
            ->update(['is_used' => true, 'used_at' => Carbon::now()]);

        // Revoke old access token
        DB::table('oauth_access_tokens')
            ->where('id', $refreshTokenData->access_token_id)
            ->update(['revoked' => true]);

        // Create new tokens
        return $this->createSecureToken($user, $deviceFingerprint, [
            'refreshed_from' => $refreshTokenData->access_token_id,
        ]);
    }

    /**
     * Store token metadata in cache and database
     */
    private function storeTokenMetadata(string $tokenId, array $data): void
    {
        // Store in cache for fast access
        Cache::put("token_metadata:{$tokenId}", $data, 86400); // 24 hours

        // Store in database for persistence
        try {
            DB::table('token_metadata')->insert([
                'token_id' => $tokenId,
                'user_id' => $data['user_id'],
                'device_fingerprint' => $data['device_fingerprint'],
                'ip_address' => $data['ip_address'],
                'user_agent' => $data['user_agent'],
                'expires_at' => $data['expires_at'],
                'created_at' => $data['created_at'],
                'last_used_at' => $data['last_used_at'],
                'is_revoked' => false,
                'additional_claims' => $data['additional_claims'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store token metadata', [
                'token_id' => $tokenId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update token last used timestamp
     */
    public function updateTokenLastUsed(string $tokenId): void
    {
        $now = Carbon::now();

        // Update cache
        $metadata = Cache::get("token_metadata:{$tokenId}");
        if ($metadata) {
            $metadata['last_used_at'] = $now;
            Cache::put("token_metadata:{$tokenId}", $metadata, 86400);
        }

        // Update database
        DB::table('token_metadata')
            ->where('token_id', $tokenId)
            ->update(['last_used_at' => $now]);
    }

    /**
     * Revoke token and all associated refresh tokens
     */
    public function revokeToken(string $tokenId): bool
    {
        try {
            // Revoke access token
            DB::table('oauth_access_tokens')
                ->where('id', $tokenId)
                ->update(['revoked' => true]);
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $tokenId)
                ->update(['revoked' => true]);

            // Revoke all refresh tokens for this access token
            DB::table('refresh_tokens')
                ->where('access_token_id', $tokenId)
                ->update(['is_used' => true, 'used_at' => Carbon::now()]);

            // Update metadata
            DB::table('token_metadata')
                ->where('token_id', $tokenId)
                ->update(['is_revoked' => true, 'revoked_at' => Carbon::now()]);

            // Remove from cache
            Cache::forget("token_metadata:{$tokenId}");

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to revoke token', [
                'token_id' => $tokenId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Revoke all tokens for a user
     */
    public function revokeAllUserTokens(int $userId): int
    {
        $revokedCount = 0;

        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->where('revoked', false)
            ->get();

        foreach ($tokens as $token) {
            if ($this->revokeToken($token->id)) {
                $revokedCount++;
            }
        }

        Log::channel('security')->info('All user tokens revoked', [
            'user_id' => $userId,
            'tokens_revoked' => $revokedCount,
        ]);

        return $revokedCount;
    }

    /**
     * Revoke tokens for specific device
     */
    public function revokeTokensForDevice(User $user, string $deviceFingerprint): int
    {
        $revokedCount = 0;

        $tokenMetadata = DB::table('token_metadata')
            ->where('user_id', $user->id)
            ->where('device_fingerprint', $deviceFingerprint)
            ->where('is_revoked', false)
            ->get();

        foreach ($tokenMetadata as $metadata) {
            if ($this->revokeToken($metadata->token_id)) {
                $revokedCount++;
            }
        }

        if ($revokedCount > 0) {
            Log::channel('security')->info('Device tokens revoked', [
                'user_id' => $user->id,
                'device_fingerprint' => substr($deviceFingerprint, 0, 12).'...',
                'tokens_revoked' => $revokedCount,
            ]);
        }

        return $revokedCount;
    }

    /**
     * Get active sessions for user
     */
    public function getUserActiveSessions(int $userId): array
    {
        $sessions = DB::table('token_metadata as tm')
            ->join('oauth_access_tokens as oat', 'tm.token_id', '=', 'oat.id')
            ->where('tm.user_id', $userId)
            ->where('tm.is_revoked', false)
            ->where('oat.revoked', false)
            ->where('oat.expires_at', '>', Carbon::now())
            ->select([
                'tm.token_id',
                'tm.device_fingerprint',
                'tm.ip_address',
                'tm.created_at',
                'tm.last_used_at',
                'oat.expires_at',
            ])
            ->get()
            ->toArray();

        return array_map(function ($session) {
            return [
                'token_id' => $session->token_id,
                'device_fingerprint' => substr($session->device_fingerprint, 0, 12).'...',
                'ip_address' => $session->ip_address,
                'created_at' => $session->created_at,
                'last_used_at' => $session->last_used_at,
                'expires_at' => $session->expires_at,
                'is_current' => request()->bearerToken() && $this->isCurrentToken($session->token_id),
            ];
        }, $sessions);
    }

    /**
     * Check if token is currently being used
     */
    private function isCurrentToken(string $tokenId): bool
    {
        $currentToken = request()->user()->token();

        return $currentToken && $currentToken->id === $tokenId;
    }

    /**
     * Clean up expired tokens and refresh tokens
     */
    public function cleanupExpiredTokens(): array
    {
        $stats = [
            'expired_access_tokens' => 0,
            'expired_refresh_tokens' => 0,
            'expired_metadata' => 0,
        ];

        try {
            // Clean up expired refresh tokens
            $stats['expired_refresh_tokens'] = DB::table('refresh_tokens')
                ->where('expires_at', '<', Carbon::now())
                ->delete();

            // Clean up expired token metadata
            $stats['expired_metadata'] = DB::table('token_metadata')
                ->whereNull('session_family_id')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('oauth_access_tokens')
                        ->whereColumn('oauth_access_tokens.id', 'token_metadata.token_id')
                        ->where('oauth_access_tokens.expires_at', '<', Carbon::now());
                })
                ->delete();

            // Note: oauth_access_tokens cleanup is handled by Laravel Passport

            Log::info('Token cleanup completed', $stats);
        } catch (\Exception $e) {
            Log::error('Token cleanup failed', [
                'error' => $e->getMessage(),
                'stats' => $stats,
            ]);
        }

        return $stats;
    }

    /**
     * Validate token integrity and security
     */
    public function validateTokenSecurity(string $tokenId, string $currentDeviceFingerprint): array
    {
        $issues = [];

        $metadata = Cache::get("token_metadata:{$tokenId}");
        if (! $metadata) {
            $metadata = DB::table('token_metadata')
                ->where('token_id', $tokenId)
                ->first();

            if (! $metadata) {
                $issues[] = 'TOKEN_METADATA_MISSING';

                return $issues;
            }

            $metadata = (array) $metadata;
        }

        // Check device fingerprint consistency
        if ($metadata['device_fingerprint'] !== $currentDeviceFingerprint) {
            $issues[] = 'DEVICE_FINGERPRINT_MISMATCH';
            Log::channel('security')->warning('Token device fingerprint mismatch', [
                'token_id' => $tokenId,
                'stored_fingerprint' => substr($metadata['device_fingerprint'], 0, 12).'...',
                'current_fingerprint' => substr($currentDeviceFingerprint, 0, 12).'...',
            ]);
        }

        // Check for suspicious IP changes
        $currentIp = request()->ip();
        if ($metadata['ip_address'] !== $currentIp) {
            $issues[] = 'IP_ADDRESS_CHANGED';
            Log::channel('security')->info('Token IP address changed', [
                'token_id' => $tokenId,
                'original_ip' => $metadata['ip_address'],
                'current_ip' => $currentIp,
            ]);
        }

        // Check token age
        $tokenAge = Carbon::parse($metadata['created_at'])->diffInHours(Carbon::now());
        if ($tokenAge > 72) { // More than 3 days
            $issues[] = 'TOKEN_TOO_OLD';
        }

        // Check last usage
        $lastUsed = Carbon::parse($metadata['last_used_at']);
        if ($lastUsed->diffInHours(Carbon::now()) > 24) {
            $issues[] = 'TOKEN_INACTIVE';
        }

        return $issues;
    }

    /**
     * Get token statistics for monitoring
     */
    public function getTokenStatistics(): array
    {
        return [
            'active_tokens' => DB::table('oauth_access_tokens')
                ->where('revoked', false)
                ->where('expires_at', '>', Carbon::now())
                ->count(),

            'expired_tokens' => DB::table('oauth_access_tokens')
                ->where('expires_at', '<', Carbon::now())
                ->count(),

            'revoked_tokens' => DB::table('oauth_access_tokens')
                ->where('revoked', true)
                ->count(),

            'active_refresh_tokens' => DB::table('refresh_tokens')
                ->where('is_used', false)
                ->where('expires_at', '>', Carbon::now())
                ->count(),

            'used_refresh_tokens' => DB::table('refresh_tokens')
                ->where('is_used', true)
                ->count(),

            'unique_active_users' => DB::table('oauth_access_tokens')
                ->where('revoked', false)
                ->where('expires_at', '>', Carbon::now())
                ->distinct('user_id')
                ->count('user_id'),

            'tokens_created_today' => DB::table('oauth_access_tokens')
                ->whereDate('created_at', Carbon::today())
                ->count(),

            'suspicious_activities' => DB::table('suspicious_activities')
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];
    }

    /**
     * Create tokens with enhanced security (wrapper method for LoginController compatibility)
     */
    public function createTokens(User $user, string $ipAddress, string $userAgent, ?string $serialNumber = null): array
    {
        // Generate device fingerprint from available data
        $deviceFingerprint = $this->generateDeviceFingerprint($ipAddress, $userAgent, $serialNumber);

        // Create additional claims with context
        $additionalClaims = [
            'login_ip' => $ipAddress,
            'login_user_agent' => $userAgent,
            'serial_number' => $serialNumber,
            'login_timestamp' => Carbon::now()->toISOString(),
        ];

        // Use the existing createSecureToken method
        return $this->createSecureToken($user, $deviceFingerprint, $additionalClaims);
    }

    /**
     * Generate device fingerprint from available data
     */
    private function generateDeviceFingerprint(string $ipAddress, string $userAgent, ?string $serialNumber = null): string
    {
        $components = [
            $ipAddress,
            $userAgent,
            $serialNumber ?? 'no_serial',
        ];

        return hash('sha256', implode('|', $components));
    }
}
