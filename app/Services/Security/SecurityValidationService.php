<?php

namespace App\Services\Security;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SecurityValidationService
{
    /**
     * Check if we're in development mode for relaxed security
     */
    private function isDevelopmentMode(): bool
    {
        return config('app.env') === 'local' || config('app.debug') === true;
    }

    /**
     * IP blacklist patterns (can be moved to config or database)
     * Note: Private/local IPs are allowed for development and internal use
     */
    private const IP_BLACKLIST = [
        // Add known malicious IP ranges here
        // '1.2.3.0/24', // Example malicious range
        // Note: Removed private ranges (10.x.x.x, 172.16-31.x.x, 192.168.x.x) for internal use
    ];

    /**
     * Suspicious user agent patterns
     * Only truly malicious/automated patterns - legitimate mobile apps allowed
     */
    private const SUSPICIOUS_USER_AGENTS = [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'havij',
        'w3af',
        'burpsuite',
        'acunetix',
        'netsparker',
        // Note: Removed common tools used in development (curl, postman, etc.)
    ];

    /**
     * SQL injection patterns - More specific to avoid false positives
     */
    private const SQL_INJECTION_PATTERNS = [
        '/\\\\x27|\\\\x22/i', // Hex encoded quotes
        '/(union|select)\\s+(all\\s+)?\\w+\\s+from/i', // More specific SQL union/select
        '/--\\s*$/m', // SQL comments at end of line
        '/\\|\\||\\&\\&/i', // SQL logical operators
        '/exec\\s+(sp_|xp_)\\w+/i', // SQL Server stored procedures
    ];

    /**
     * XSS patterns
     */
    private const XSS_PATTERNS = [
        '/<script[^>]*>.*?<\/script>/six',
        '/<iframe[^>]*>.*?<\/iframe>/six',
        '/javascript:/ix',
        '/on\w+\s*=/ix',
        '/<\w+[^>]*\s+on\w+\s*=/ix',
    ];

    /**
     * Command injection patterns
     */
    private const COMMAND_INJECTION_PATTERNS = [
        '/[;&|`]/',
        '/\$\(.*\)/',
        '/`.*`/',
        '/\|\|/',
        '/&&/',
    ];

    /**
     * Validate request for security threats
     */
    public function validateRequest(Request $request): array
    {
        $threats = [];
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $isDevelopment = $this->isDevelopmentMode();

        // Only check IP blacklist in production or for truly malicious IPs
        if (! $isDevelopment && $this->isBlacklistedIp($ip)) {
            $threats[] = [
                'type' => 'BLACKLISTED_IP',
                'severity' => 'high',
                'message' => 'IP address is blacklisted',
                'data' => ['ip' => $ip],
            ];
        }

        // Check for suspicious user agents (less strict in development)
        if (! $isDevelopment && $this->isSuspiciousUserAgent($userAgent)) {
            $threats[] = [
                'type' => 'SUSPICIOUS_USER_AGENT',
                'severity' => 'medium',
                'message' => 'Suspicious user agent detected',
                'data' => ['user_agent' => substr($userAgent, 0, 100)],
            ];
        }

        // Always check for malicious patterns but with context
        $maliciousPatterns = $this->checkMaliciousPatterns($request->all(), $isDevelopment);
        if (! empty($maliciousPatterns)) {
            $threats = array_merge($threats, $maliciousPatterns);
        }

        // Check for abnormal request frequency (relaxed in development)
        if (! $isDevelopment) {
            $frequencyThreat = $this->checkRequestFrequency($ip);
            if ($frequencyThreat) {
                $threats[] = $frequencyThreat;
            }
        }

        // Log threats if any
        if (! empty($threats)) {
            $this->logSecurityThreats($request, $threats);
        }

        return $threats;
    }

    /**
     * Check if IP is blacklisted
     */
    private function isBlacklistedIp(string $ip): bool
    {
        // Check static blacklist
        foreach (self::IP_BLACKLIST as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        // Check dynamic blacklist from cache
        $dynamicBlacklist = Cache::get('dynamic_ip_blacklist', []);

        return in_array($ip, $dynamicBlacklist);
    }

    /**
     * Check if IP is in range
     */
    private function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        [$subnet, $mask] = explode('/', $range);
        $subnet = ip2long($subnet);
        $ip = ip2long($ip);
        $mask = ~((1 << (32 - $mask)) - 1);

        return ($ip & $mask) === ($subnet & $mask);
    }

    /**
     * Check for suspicious user agents
     */
    private function isSuspiciousUserAgent(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false; // Allow empty user agent for mobile apps
        }

        $userAgent = strtolower($userAgent);

        // Whitelist legitimate user agents
        $legitimatePatterns = [
            'mozilla', 'chrome', 'safari', 'firefox', 'edge', 'opera',
            'android', 'iphone', 'ipad', 'mobile', 'okhttp', 'alamofire',
            'cfnetwork', 'dart', 'flutter', 'react-native',
        ];

        // If it contains legitimate patterns, it's probably not suspicious
        foreach ($legitimatePatterns as $legitimate) {
            if (strpos($userAgent, $legitimate) !== false) {
                return false;
            }
        }

        // Check against known malicious patterns
        foreach (self::SUSPICIOUS_USER_AGENTS as $pattern) {
            if (strpos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false; // Default to not suspicious for unknown user agents
    }

    /**
     * Check for malicious patterns in input data
     */
    private function checkMaliciousPatterns(array $data, bool $isDevelopment = false): array
    {
        $threats = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $nestedThreats = $this->checkMaliciousPatterns($value, $isDevelopment);
                $threats = array_merge($threats, $nestedThreats);

                continue;
            }

            if (! is_string($value)) {
                continue;
            }

            // Check for SQL injection (relaxed in development)
            if (! $isDevelopment && $this->containsSqlInjection($value)) {
                $threats[] = [
                    'type' => 'SQL_INJECTION',
                    'severity' => 'high',
                    'message' => 'Potential SQL injection detected',
                    'data' => [
                        'field' => $key,
                        'value' => substr($value, 0, 100),
                    ],
                ];
            }

            // Check for XSS (relaxed in development)
            if (! $isDevelopment && $this->containsXss($value)) {
                $threats[] = [
                    'type' => 'XSS_ATTEMPT',
                    'severity' => 'high',
                    'message' => 'Potential XSS attempt detected',
                    'data' => [
                        'field' => $key,
                        'value' => substr($value, 0, 100),
                    ],
                ];
            }

            // Check for command injection
            if ($this->containsCommandInjection($value)) {
                $threats[] = [
                    'type' => 'COMMAND_INJECTION',
                    'severity' => 'high',
                    'message' => 'Potential command injection detected',
                    'data' => [
                        'field' => $key,
                        'value' => substr($value, 0, 100),
                    ],
                ];
            }

            // Check for suspicious encoding
            if ($this->containsSuspiciousEncoding($value)) {
                $threats[] = [
                    'type' => 'SUSPICIOUS_ENCODING',
                    'severity' => 'medium',
                    'message' => 'Suspicious encoding detected',
                    'data' => [
                        'field' => $key,
                        'value' => substr($value, 0, 100),
                    ],
                ];
            }
        }

        return $threats;
    }

    /**
     * Check for SQL injection patterns
     */
    private function containsSqlInjection(string $value): bool
    {
        foreach (self::SQL_INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for XSS patterns
     */
    private function containsXss(string $value): bool
    {
        foreach (self::XSS_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for command injection patterns
     */
    private function containsCommandInjection(string $value): bool
    {
        foreach (self::COMMAND_INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for suspicious encoding
     */
    private function containsSuspiciousEncoding(string $value): bool
    {
        // Check for excessive URL encoding
        $urlEncodedCount = preg_match_all('/%[0-9A-F]{2}/i', $value);
        if ($urlEncodedCount > 5) {
            return true;
        }

        // Check for Unicode evasion
        if (strpos($value, '\\u') !== false && preg_match('/\\\\u[0-9a-f]{4}/i', $value)) {
            return true;
        }

        // Check for hex encoding
        if (preg_match('/0x[0-9a-f]+/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * Check request frequency for potential DoS
     */
    private function checkRequestFrequency(string $ip): ?array
    {
        $key = "request_frequency:{$ip}";
        $requests = Cache::get($key, 0);

        // If more than 100 requests per minute, it's suspicious
        if ($requests > 100) {
            return [
                'type' => 'HIGH_REQUEST_FREQUENCY',
                'severity' => 'high',
                'message' => 'Abnormally high request frequency detected',
                'data' => [
                    'requests_per_minute' => $requests,
                    'ip' => $ip,
                ],
            ];
        }

        // Increment counter
        Cache::put($key, $requests + 1, 60); // 1 minute TTL

        return null;
    }

    /**
     * Add IP to dynamic blacklist
     */
    public function addToBlacklist(string $ip, int $ttlMinutes = 60): void
    {
        $blacklist = Cache::get('dynamic_ip_blacklist', []);

        if (! in_array($ip, $blacklist)) {
            $blacklist[] = $ip;
            Cache::put('dynamic_ip_blacklist', $blacklist, $ttlMinutes * 60);

            Log::channel('security')->warning('IP added to dynamic blacklist', [
                'ip' => $ip,
                'ttl_minutes' => $ttlMinutes,
                'timestamp' => Carbon::now()->toISOString(),
            ]);
        }
    }

    /**
     * Remove IP from dynamic blacklist
     */
    public function removeFromBlacklist(string $ip): void
    {
        $blacklist = Cache::get('dynamic_ip_blacklist', []);
        $key = array_search($ip, $blacklist);

        if ($key !== false) {
            unset($blacklist[$key]);
            Cache::put('dynamic_ip_blacklist', array_values($blacklist), 3600); // 1 hour TTL

            Log::channel('security')->info('IP removed from dynamic blacklist', [
                'ip' => $ip,
                'timestamp' => Carbon::now()->toISOString(),
            ]);
        }
    }

    /**
     * Clear all dynamic blacklist (for development)
     */
    public function clearBlacklist(): void
    {
        Cache::forget('dynamic_ip_blacklist');
        Log::channel('security')->info('Dynamic blacklist cleared', [
            'timestamp' => Carbon::now()->toISOString(),
        ]);
    }

    /**
     * Log security threats
     */
    private function logSecurityThreats(Request $request, array $threats): void
    {
        $highSeverityCount = count(array_filter($threats, fn ($t) => $t['severity'] === 'high'));

        $logData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $request->path(),
            'method' => $request->method(),
            'threats' => $threats,
            'high_severity_count' => $highSeverityCount,
            'timestamp' => Carbon::now()->toISOString(),
        ];

        if ($highSeverityCount > 0) {
            Log::channel('security')->error('High severity security threats detected', $logData);
        } else {
            Log::channel('security')->warning('Security threats detected', $logData);
        }
    }

    /**
     * Get threat summary for request
     */
    public function getThreatSummary(array $threats): array
    {
        $summary = [
            'total' => count($threats),
            'high' => 0,
            'medium' => 0,
            'low' => 0,
            'types' => [],
        ];

        foreach ($threats as $threat) {
            $summary[$threat['severity']]++;
            if (! in_array($threat['type'], $summary['types'])) {
                $summary['types'][] = $threat['type'];
            }
        }

        return $summary;
    }

    /**
     * Check if request should be blocked based on threats
     */
    public function shouldBlockRequest(array $threats): bool
    {
        $isDevelopment = $this->isDevelopmentMode();

        // Never block in development mode
        if ($isDevelopment) {
            return false;
        }

        $highSeverityCount = count(array_filter($threats, fn ($t) => $t['severity'] === 'high'));
        $mediumSeverityCount = count(array_filter($threats, fn ($t) => $t['severity'] === 'medium'));

        // More lenient blocking policy
        // Block only if there are multiple high severity threats or excessive medium severity threats
        return $highSeverityCount >= 3 || $mediumSeverityCount > 5;
    }
}
