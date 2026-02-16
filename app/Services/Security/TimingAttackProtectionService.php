<?php

namespace App\Services\Security;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TimingAttackProtectionService
{
    /**
     * Ensure minimum delay to prevent timing attacks
     */
    public static function ensureMinimumDelay(float $startTime, float $minimumSeconds = 0.2): void
    {
        $elapsed = microtime(true) - $startTime;
        $remaining = $minimumSeconds - $elapsed;

        if ($remaining > 0) {
            // Use usleep for microsecond precision
            usleep((int) ($remaining * 1000000));
        }
    }

    /**
     * Simulate password hash check for non-existent users
     */
    public static function simulatePasswordCheck(string $password): bool
    {
        // Use a dummy hash that takes similar time as a real bcrypt hash
        $dummyHash = '$2y$10$dummyHashToPreventTimingAttacksUsedForNonExistentUsers.';
        Hash::check($password, $dummyHash);

        // Always return false for dummy checks
        return false;
    }

    /**
     * Perform authentication with timing attack protection
     */
    public static function authenticateWithTimingProtection(
        ?object $user,
        string $password,
        float $startTime
    ): array {
        $userExists = ! is_null($user);
        $passwordValid = false;
        $userActive = false;

        if ($userExists) {
            // Real user - check password and status
            $passwordValid = Hash::check($password, $user->password);
            $userActive = $user->statusid !== 'D';
        } else {
            // Simulate operations for non-existent user to maintain timing consistency
            static::simulatePasswordCheck($password);
            // Simulate status check
        }

        // Ensure minimum delay regardless of execution path
        static::ensureMinimumDelay($startTime);

        return [
            'user_exists' => $userExists,
            'password_valid' => $passwordValid,
            'user_active' => $userActive,
            'authenticated' => $userExists && $passwordValid && $userActive,
        ];
    }

    /**
     * Add random delay to make timing analysis more difficult
     */
    public static function addRandomJitter(int $minMicroseconds = 1000, int $maxMicroseconds = 5000): void
    {
        $delay = random_int($minMicroseconds, $maxMicroseconds);
        usleep($delay);
    }

    /**
     * Execute operation with consistent timing
     */
    public static function executeWithConsistentTiming(callable $operation, float $targetTimeSeconds = 0.2): mixed
    {
        $startTime = microtime(true);

        try {
            $result = $operation();
        } catch (\Exception $e) {
            // Ensure timing consistency even when exceptions occur
            static::ensureMinimumDelay($startTime, $targetTimeSeconds);
            throw $e;
        }

        static::ensureMinimumDelay($startTime, $targetTimeSeconds);

        return $result;
    }

    /**
     * Simulate database operations for timing consistency
     */
    public static function simulateDatabaseOperation(int $operationType = 1): void
    {
        switch ($operationType) {
            case 1: // Simulate SELECT query
                // Perform a lightweight operation similar to user lookup
                hash('sha256', uniqid());
                break;

            case 2: // Simulate UPDATE query
                // Perform operations similar to session update
                for ($i = 0; $i < 10; $i++) {
                    hash('md5', $i);
                }
                break;

            case 3: // Simulate INSERT query
                // Perform operations similar to log insertion
                $data = [
                    'timestamp' => Carbon::now(),
                    'data' => str_repeat('x', 100),
                ];
                json_encode($data);
                break;
        }
    }

    /**
     * Get timing-safe authentication result
     */
    public static function getTimingSafeAuthResult(
        bool $success,
        string $successMessage,
        string $errorMessage
    ): array {
        // Always construct both success and error responses to maintain timing
        $successResponse = [
            'success' => true,
            'message' => $successMessage,
        ];

        $errorResponse = [
            'success' => false,
            'message' => $errorMessage,
        ];

        // Return the appropriate response
        return $success ? $successResponse : $errorResponse;
    }

    /**
     * Validate timing attack resistance for a function
     */
    public static function validateTimingConsistency(callable $function, int $iterations = 10): array
    {
        $timings = [];

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $function();
            $end = microtime(true);

            $timings[] = ($end - $start) * 1000; // Convert to milliseconds
        }

        $average = array_sum($timings) / count($timings);
        $variance = array_sum(array_map(function ($time) use ($average) {
            return pow($time - $average, 2);
        }, $timings)) / count($timings);

        $standardDeviation = sqrt($variance);
        $coefficientOfVariation = ($standardDeviation / $average) * 100;

        return [
            'timings' => $timings,
            'average_ms' => round($average, 3),
            'std_deviation_ms' => round($standardDeviation, 3),
            'coefficient_of_variation' => round($coefficientOfVariation, 2),
            'min_ms' => round(min($timings), 3),
            'max_ms' => round(max($timings), 3),
            'is_timing_consistent' => $coefficientOfVariation < 10, // Less than 10% variation
        ];
    }

    /**
     * Create timing-attack resistant comparison
     */
    public static function timingSafeEquals(string $a, string $b): bool
    {
        // Use hash_equals for timing-safe string comparison
        return hash_equals($a, $b);
    }

    /**
     * Generate consistent delays based on string content
     */
    public static function generateConsistentDelay(string $input, float $baseDelaySeconds = 0.1): void
    {
        // Create a deterministic delay based on input hash
        $hash = hash('sha256', $input);
        $delayMultiplier = hexdec(substr($hash, 0, 2)) / 255; // 0-1 range

        $delaySeconds = $baseDelaySeconds + ($delayMultiplier * 0.05); // Add up to 50ms variation

        usleep((int) ($delaySeconds * 1000000));
    }

    /**
     * Secure credential check with timing attack protection
     */
    public function secureCredentialCheck(string $username, string $password, callable $authCallback): bool
    {
        $startTime = microtime(true);

        try {
            // Execute the authentication callback
            $result = $authCallback($username, $password);
        } catch (\Exception $e) {
            // Ensure consistent timing even on exceptions
            static::ensureMinimumDelay($startTime, 0.2);
            throw $e;
        }

        // Always ensure minimum delay regardless of success/failure
        static::ensureMinimumDelay($startTime, 0.2);

        return $result;
    }

    /**
     * Simulate user check for non-existent users
     */
    public function simulateUserCheck(): void
    {
        // Simulate database lookup delay
        static::simulateDatabaseOperation(1);

        // Simulate password hashing delay
        static::simulatePasswordCheck('dummy_password');

        // Add small random jitter
        static::addRandomJitter(500, 2000);
    }

    /**
     * Instance method wrapper for ensureMinimumDelay
     */
    public function ensureMinimumDelayInstance(float $startTime, float $minimumSeconds = 0.2): void
    {
        static::ensureMinimumDelay($startTime, $minimumSeconds);
    }
}
