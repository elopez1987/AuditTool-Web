<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Resources\LocalAuthResponse;
use App\Repositories\Security\LocalPassportClientRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Guards\TokenGuard;
use Laravel\Passport\PassportUserProvider;
use League\OAuth2\Server\ResourceServer;

final class LocalAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Auth::extend('local-passport', static function (Application $app, string $name, array $config): TokenGuard {
            return new TokenGuard(
                $app->make(ResourceServer::class),
                new PassportUserProvider($app['auth']->createUserProvider($config['provider']), $config['provider']),
                $app->make(LocalPassportClientRepository::class),
                $app->make('encrypter'),
                $app->make('request')
            );
        });

        RateLimiter::for('local-app-auth', static function (Request $request): array {
            $error = static fn (Request $request, array $headers): LocalAuthResponse => new LocalAuthResponse(
                429, 'Demasiadas solicitudes. Inténtalo más tarde.', null, 'login-user', $headers
            );

            $limits = [Limit::perMinute(30)->by('ip:'.$request->ip())->response($error)];
            $username = $request->input('username');
            $expected = config('local_auth.token_app');
            $provided = $request->header('X-App-Token');
            $hasAppToken = is_string($expected) && strlen($expected) >= 32 && is_string($provided)
                && hash_equals(hash('sha256', $expected), hash('sha256', $provided));
            if ($hasAppToken && $request->route()?->getName() === 'v1.auth.login'
                && is_string($username) && trim($username) !== '') {
                $limits[] = Limit::perMinute(10)->by('account:'.hash('sha256', mb_strtolower(trim($username))))
                    ->response($error);
            }

            return $limits;
        });
    }
}
