<?php

declare(strict_types=1);

namespace App\Auth;

use App\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

final class UserRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    private const CACHE_TTL_SECONDS = 120;

    private const CACHE_PREFIX = 'auth0_user_';

    public function fromAccessToken(array $user): ?Authenticatable
    {
        $identifier = $this->extractIdentifier($user);

        if ($identifier === null) {
            return null;
        }

        return $this->findByAuth0($identifier);
    }

    public function fromSession(array $user): ?Authenticatable
    {
        $identifier = $this->extractIdentifier($user);

        if ($identifier === null) {
            Log::warning('Auth0 login: missing identifier in session data.');

            return null;
        }

        $cached = $this->withoutRecording(fn () => Cache::get(self::CACHE_PREFIX . $identifier));

        if ($cached instanceof Authenticatable) {
            return $cached;
        }

        $localUser = $this->findByAuth0($identifier)
            ?? $this->findByEmail($user['email'] ?? null);

        if ($localUser === null) {
            Log::warning('Auth0 login: user not found in local database.', [
                'auth0_identifier' => $identifier,
                'email' => $user['email'] ?? null,
            ]);

            return null;
        }

        $this->withoutRecording(
            fn () => Cache::put(self::CACHE_PREFIX . $identifier, $localUser, self::CACHE_TTL_SECONDS)
        );

        return $localUser;
    }

    private function extractIdentifier(array $user): ?string
    {
        return $user['sub'] ?? $user['auth0'] ?? null;
    }

    private function findByAuth0(string $identifier): ?User
    {
        return User::where('auth0', $identifier)->first();
    }

    private function findByEmail(?string $email): ?User
    {
        if ($email === null || $email === '') {
            return null;
        }

        return User::where('email', $email)->first();
    }

    /**
     * Workaround for Laravel Telescope potentially causing an infinite loop.
     *
     * @link https://github.com/auth0/laravel-auth0/tree/main/docs/Telescope.md
     */
    private function withoutRecording(callable $callback): mixed
    {
        $telescope = '\Laravel\Telescope\Telescope';

        if (class_exists($telescope) && method_exists($telescope, 'withoutRecording')) {
            return $telescope::withoutRecording($callback);
        }

        return $callback();
    }
}
