<?php

namespace App\Services\Security;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class AuthenticationAuditService
{
    /**
     * Log authentication attempt with comprehensive audit data
     */
    public function logAuthenticationAttempt(
        Request $request,
        string $username,
        bool $successful,
        string $reason = '',
        ?int $userId = null,
        array $additionalData = []
    ): void {
        $startTime = microtime(true);

        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $deviceFingerprint = $this->generateDeviceFingerprint($request);
        $location = $this->getLocationFromIp($ip);

        $auditData = [
            'username' => $username,
            'user_id' => $userId,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'successful' => $successful,
            'reason' => $reason,
            'country' => $location['country'] ?? null,
            'region' => $location['region'] ?? null,
            'city' => $location['city'] ?? null,
            'latitude' => $location['lat'] ?? null,
            'longitude' => $location['lon'] ?? null,
            'timezone' => $location['timezone'] ?? null,
            'device_fingerprint' => $deviceFingerprint,
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'request_headers' => $this->sanitizeHeaders($request->headers->all()),
            'session_id' => session()->getId(),
            'timestamp' => Carbon::now(),
            'processing_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
        ];

        // Add additional data if provided
        if (! empty($additionalData)) {
            $auditData = array_merge($auditData, $additionalData);
        }

        // Store in database
        $this->storeInDatabase($auditData);

        // Log to file with appropriate level
        $this->logToFile($auditData);

        // Check for suspicious activity if login was successful
        if ($successful && $userId) {
            $this->checkSuspiciousActivity($userId, $ip, $location, $deviceFingerprint);
        }

        // Check for brute force patterns
        if (! $successful) {
            $this->checkBruteForcePatterns($ip, $username);
        }
    }

    /**
     * Generate device fingerprint from request data
     */
    public function generateDeviceFingerprint(Request $request): string
    {
        $fingerprint = [
            'user_agent' => $request->userAgent(),
            'accept_language' => $request->header('Accept-Language'),
            'accept_encoding' => $request->header('Accept-Encoding'),
            'accept' => $request->header('Accept'),
            'connection' => $request->header('Connection'),
            'upgrade_insecure_requests' => $request->header('Upgrade-Insecure-Requests'),
            'sec_fetch_site' => $request->header('Sec-Fetch-Site'),
            'sec_fetch_mode' => $request->header('Sec-Fetch-Mode'),
            'sec_fetch_dest' => $request->header('Sec-Fetch-Dest'),
        ];

        // Remove null values
        $fingerprint = array_filter($fingerprint, function ($value) {
            return $value !== null;
        });

        return hash('sha256', json_encode($fingerprint));
    }

    /**
     * Get location information from IP address using multiple fallback services
     */
    private function getLocationFromIp(string $ip): array
    {
        // Skip for local/private IPs
        if ($this->isLocalIp($ip)) {
            return [
                'country' => 'Local',
                'region' => 'Development',
                'city' => 'Development',
                'lat' => null,
                'lon' => null,
                'timezone' => config('app.timezone', 'UTC'),
                'source' => 'local',
            ];
        }

        try {
            // Try primary location service
            $position = Location::get($ip);

            if ($position && $position->countryName) {
                return [
                    'country' => $position->countryName,
                    'region' => $position->regionName ?? 'Unknown',
                    'city' => $position->cityName ?? 'Unknown',
                    'lat' => $position->latitude,
                    'lon' => $position->longitude,
                    'timezone' => $position->timezone ?? config('app.timezone'),
                    'source' => 'primary',
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Primary geolocation service failed', [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback to simple IP-based detection
        try {
            $fallbackData = $this->getFallbackLocation($ip);
            if ($fallbackData) {
                return array_merge($fallbackData, ['source' => 'fallback']);
            }
        } catch (\Exception $e) {
            Log::warning('Fallback geolocation service failed', [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        // Final fallback
        return [
            'country' => 'Unknown',
            'region' => 'Unknown',
            'city' => 'Unknown',
            'lat' => null,
            'lon' => null,
            'timezone' => config('app.timezone'),
            'source' => 'unknown',
        ];
    }

    /**
     * Fallback geolocation method using free API
     */
    private function getFallbackLocation(string $ip): ?array
    {
        try {
            $response = file_get_contents("http://ip-api.com/json/{$ip}");
            $data = json_decode($response, true);

            if ($data && $data['status'] === 'success') {
                return [
                    'country' => $data['country'] ?? 'Unknown',
                    'region' => $data['regionName'] ?? 'Unknown',
                    'city' => $data['city'] ?? 'Unknown',
                    'lat' => $data['lat'] ?? null,
                    'lon' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? config('app.timezone'),
                ];
            }
        } catch (\Exception $e) {
            // Silent fail for fallback
        }

        return null;
    }

    /**
     * Check if IP is local/private
     */
    private function isLocalIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1']) ||
               filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Sanitize headers for logging
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key', 'x-auth-token'];

        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $sensitiveHeaders)) {
                $headers[$key] = ['***REDACTED***'];
            }
        }

        return $headers;
    }

    /**
     * Store audit data in database with encryption for sensitive data
     */
    private function storeInDatabase(array $auditData): void
    {
        try {
            // Encrypt sensitive data
            $encryptedData = [
                'user_agent' => isset($auditData['user_agent']) ? Crypt::encryptString($auditData['user_agent']) : null,
                'request_headers' => isset($auditData['request_headers']) ? Crypt::encryptString(json_encode($auditData['request_headers'])) : null,
            ];

            DB::table('authentication_audit_logs')->insert([
                'username' => $auditData['username'],
                'user_id' => $auditData['user_id'],
                'ip_address' => $auditData['ip_address'],
                'user_agent_encrypted' => $encryptedData['user_agent'],
                'successful' => $auditData['successful'],
                'reason' => $auditData['reason'],
                'country' => $auditData['country'],
                'region' => $auditData['region'],
                'city' => $auditData['city'],
                'latitude' => $auditData['latitude'],
                'longitude' => $auditData['longitude'],
                'timezone' => $auditData['timezone'],
                'device_fingerprint' => $auditData['device_fingerprint'],
                'request_method' => $auditData['request_method'],
                'request_path' => $auditData['request_path'],
                'request_headers_encrypted' => $encryptedData['request_headers'],
                'session_id' => $auditData['session_id'],
                'processing_time_ms' => $auditData['processing_time_ms'],
                'created_at' => $auditData['timestamp'],
                'updated_at' => $auditData['timestamp'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store authentication audit in database', [
                'error' => $e->getMessage(),
                'username' => $auditData['username'] ?? 'unknown',
            ]);
        }
    }

    /**
     * Log to file with appropriate level and structured data
     */
    private function logToFile(array $auditData): void
    {
        $logLevel = $auditData['successful'] ? 'info' : 'warning';
        $message = $auditData['successful'] ? 'Successful authentication' : 'Failed authentication attempt';

        // Prepare safe log data (no sensitive info)
        $logData = [
            'username' => $auditData['username'],
            'ip' => $auditData['ip_address'],
            'location' => ($auditData['city'] ?? 'Unknown') . ', ' . ($auditData['country'] ?? 'Unknown'),
            'reason' => $auditData['reason'],
            'device_fingerprint' => substr($auditData['device_fingerprint'], 0, 12) . '...',
            'path' => $auditData['request_path'],
            'processing_time_ms' => $auditData['processing_time_ms'],
        ];

        Log::channel('auth')->{$logLevel}($message, $logData);

        // Also log to security channel for failed attempts
        if (! $auditData['successful']) {
            Log::channel('security')->warning('Authentication failure', $logData);
        }
    }

    /**
     * Check for suspicious activity patterns
     */
    private function checkSuspiciousActivity(int $userId, string $ip, array $location, string $deviceFingerprint): void
    {
        $user = User::find($userId);
        if (! $user) {
            return;
        }

        // Check for new location
        $this->checkNewLocation($user, $location);

        // Check for new device
        $this->checkNewDevice($user, $deviceFingerprint);

        // Check for multiple IPs in short time
        $this->checkMultipleIPs($user, $ip);

        // Check for rapid login attempts from different locations
        $this->checkRapidLocationChanges($user, $location);
    }

    /**
     * Check for new location access
     */
    private function checkNewLocation(User $user, array $location): void
    {
        if ($location['country'] === 'Local' || $location['country'] === 'Unknown') {
            return;
        }

        $recentLocation = DB::table('authentication_audit_logs')
            ->where('user_id', $user->id)
            ->where('successful', true)
            ->where('created_at', '>', Carbon::now()->subDays(30))
            ->whereNotNull('country')
            ->where('country', '!=', 'Local')
            ->where('country', '!=', 'Unknown')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLocation && $recentLocation->country !== $location['country']) {
            $this->logSuspiciousActivity($user->id, 'NEW_LOCATION', [
                'previous_location' => $recentLocation->city . ', ' . $recentLocation->country,
                'new_location' => $location['city'] . ', ' . $location['country'],
                'time_between' => Carbon::parse($recentLocation->created_at)->diffInHours(Carbon::now()) . ' hours',
            ]);
        }
    }

    /**
     * Check for new device access
     */
    private function checkNewDevice(User $user, string $deviceFingerprint): void
    {
        $knownDevice = DB::table('authentication_audit_logs')
            ->where('user_id', $user->id)
            ->where('successful', true)
            ->where('device_fingerprint', $deviceFingerprint)
            ->where('created_at', '>', Carbon::now()->subDays(30))
            ->exists();

        if (! $knownDevice) {
            $this->logSuspiciousActivity($user->id, 'NEW_DEVICE', [
                'device_fingerprint' => substr($deviceFingerprint, 0, 12) . '...',
                'first_seen' => Carbon::now()->toISOString(),
            ]);
        }
    }

    /**
     * Check for multiple IPs in short period
     */
    private function checkMultipleIPs(User $user, string $currentIp): void
    {
        $recentIPs = DB::table('authentication_audit_logs')
            ->where('user_id', $user->id)
            ->where('successful', true)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->distinct()
            ->pluck('ip_address')
            ->toArray();

        if (count($recentIPs) > 3 && ! in_array($currentIp, $recentIPs)) {
            $this->logSuspiciousActivity($user->id, 'MULTIPLE_IPS', [
                'ip_count' => count($recentIPs) + 1,
                'current_ip' => $currentIp,
                'other_ips' => array_slice($recentIPs, 0, 5), // Limit for logging
                'timeframe' => '1 hour',
            ]);
        }
    }

    /**
     * Check for rapid location changes
     */
    private function checkRapidLocationChanges(User $user, array $currentLocation): void
    {
        if ($currentLocation['country'] === 'Local' || $currentLocation['country'] === 'Unknown') {
            return;
        }

        $recentLogin = DB::table('authentication_audit_logs')
            ->where('user_id', $user->id)
            ->where('successful', true)
            ->where('created_at', '>', Carbon::now()->subHours(2))
            ->whereNotNull('country')
            ->where('country', '!=', 'Local')
            ->where('country', '!=', 'Unknown')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLogin && $recentLogin->country !== $currentLocation['country']) {
            $timeDiff = Carbon::parse($recentLogin->created_at)->diffInMinutes(Carbon::now());

            if ($timeDiff < 30) { // Suspicious if location changed in less than 30 minutes
                $this->logSuspiciousActivity($user->id, 'RAPID_LOCATION_CHANGE', [
                    'previous_location' => $recentLogin->city . ', ' . $recentLogin->country,
                    'new_location' => $currentLocation['city'] . ', ' . $currentLocation['country'],
                    'time_between_minutes' => $timeDiff,
                ]);
            }
        }
    }

    /**
     * Check for brute force patterns
     */
    private function checkBruteForcePatterns(string $ip, string $username): void
    {
        // Check failed attempts from same IP
        $ipFailures = DB::table('authentication_audit_logs')
            ->where('ip_address', $ip)
            ->where('successful', false)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->count();

        if ($ipFailures >= 10) {
            Log::channel('security')->error('Potential brute force attack detected', [
                'ip' => $ip,
                'failed_attempts' => $ipFailures,
                'timeframe' => '1 hour',
                'last_username_attempted' => $username,
            ]);
        }

        // Check failed attempts for same username
        $userFailures = DB::table('authentication_audit_logs')
            ->where('username', $username)
            ->where('successful', false)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->count();

        if ($userFailures >= 5) {
            Log::channel('security')->warning('Multiple failed login attempts for user', [
                'username' => $username,
                'failed_attempts' => $userFailures,
                'timeframe' => '1 hour',
            ]);
        }
    }

    /**
     * Log suspicious activity
     */
    private function logSuspiciousActivity(int $userId, string $type, array $details): void
    {
        Log::channel('security')->warning('Suspicious authentication activity detected', [
            'user_id' => $userId,
            'type' => $type,
            'details' => $details,
            'timestamp' => Carbon::now()->toISOString(),
        ]);

        // Could also store in separate suspicious_activities table for further analysis
        DB::table('suspicious_activities')->insert([
            'user_id' => $userId,
            'activity_type' => $type,
            'details' => json_encode($details),
            'severity' => $this->getSeverityLevel($type),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Get severity level for suspicious activity type
     */
    private function getSeverityLevel(string $type): string
    {
        $severityMap = [
            'NEW_LOCATION' => 'medium',
            'NEW_DEVICE' => 'low',
            'MULTIPLE_IPS' => 'high',
            'RAPID_LOCATION_CHANGE' => 'high',
        ];

        return $severityMap[$type] ?? 'low';
    }

    /**
     * Log successful authentication (wrapper for LoginController compatibility)
     */
    public function logSuccessfulLogin(
        User $user,
        string $ipAddress,
        string $userAgent,
        array $requestData = [],
        array $tokenData = []
    ): void {
        $request = request();

        // Create additional data from token info if provided
        $additionalData = [];
        if (! empty($tokenData)) {
            $additionalData['token_data'] = [
                'access_token_id' => $tokenData['token_id'] ?? null,
                'refresh_token_id' => $tokenData['refresh_token_id'] ?? null,
                'token_expires_at' => $tokenData['expires_at'] ?? null,
                'token_type' => $tokenData['token_type'] ?? 'Bearer',
            ];
        }

        if (! empty($requestData)) {
            $additionalData['request_data'] = $this->sanitizeRequestData($requestData);
        }

        $this->logAuthenticationAttempt(
            $request,
            $user->username,
            true,
            'login_successful',
            $user->id,
            $additionalData
        );
    }

    /**
     * Log failed authentication attempt (wrapper for LoginController compatibility)
     */
    public function logFailedAttempt(
        string $username,
        string $ipAddress,
        string $userAgent,
        string $reason,
        array $requestData = []
    ): void {
        $request = request();

        $additionalData = [];
        if (! empty($requestData)) {
            $additionalData['request_data'] = $this->sanitizeRequestData($requestData);
        }

        $this->logAuthenticationAttempt(
            $request,
            $username,
            false,
            $reason,
            null, // No user ID for failed attempts
            $additionalData
        );
    }

    /**
     * Sanitize request data for logging (remove sensitive information)
     */
    private function sanitizeRequestData(array $requestData): array
    {
        $sanitized = $requestData;

        // Remove or mask sensitive fields
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key'];

        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '***REDACTED***';
            }
        }

        return $sanitized;
    }
}
