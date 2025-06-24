<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\User;
use Auth0\Laravel\{UserRepositoryAbstract, UserRepositoryContract};
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    public function fromAccessToken(array $user): ?Authenticatable
    {
        $identifier = $user['sub'] ?? $user['auth0'] ?? null;

        if (null === $identifier) {
            return null;
        }
        return \App\User::where('auth0', $identifier)->first();
    }

    public function fromSession(array $user): ?Authenticatable
    {
        $identifier = $user['sub'] ?? $user['auth0'] ?? null;
        $profile = [
            'auth0' => $identifier,
            'name' => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
//            'email_verified' => in_array($user['email_verified'], [1, true], true),
        ];
        $cached = $this->withoutRecording(fn() => Cache::get('auth0_user_' . $identifier));

        if ($cached) {
            return $cached;
        }

        $user = null;
        if (null !== $identifier) {
            $user = \App\User::where('auth0', $identifier)->first();
        }

        if (null === $user && isset($user['email'])) {
            $user = \App\User::where('email', $user['email'])->first();
        }

        if (null !== $user) {
            $updates = [];

            if ($user->auth0 !== $profile['auth0']) {
                $updates['auth0'] = $profile['auth0'];
            }

//            if ($user->name !== $profile['name']) {
//                $updates['name'] = $profile['name'];
//            }
//
//            if ($user->email !== $profile['email']) {
//                $updates['email'] = $profile['email'];
//            }
//
//            $emailVerified = in_array($user->email_verified, [1, true], true);

//            if ($emailVerified !== $profile['email_verified']) {
//                $updates['email_verified'] = $profile['email_verified'];
//            }

            if ([] !== $updates) {
                $user->update($updates);
                $user->save();
            }

            if ([] === $updates && null !== $cached) {
                return $user;
            }
        }

        if (null === $user) {
            $profile['password'] = Hash::make(Str::random(32));

            $user = \App\User::create($profile);
        }

        $this->withoutRecording(fn() => Cache::put('auth0_user_' . $identifier, $user, 30));

        return $user;
    }

    /**
     * Workaround for Laravel Telescope potentially causing an infinite loop.
     * @link https://github.com/auth0/laravel-auth0/tree/main/docs/Telescope.md
     *
     * @param callable $callback
     */
    private function withoutRecording($callback): mixed
    {
        $telescope = '\Laravel\Telescope\Telescope';

        if (class_exists($telescope)) {
            return Laravel\Telescope\Telescope::withoutRecording($callback);
        }

        return call_user_func($callback);
    }


}
