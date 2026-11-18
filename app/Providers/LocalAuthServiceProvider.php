<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Middleware\AuditLocalAuthentication;
use App\Http\Resources\LocalAuthResponse;
use App\Models\Security\LocalAppUser;
use App\Models\Security\Users\StorageUserDevice;
use App\Observers\Security\LocalSessionDeviceObserver;
use App\Observers\Security\LocalSessionUserObserver;
use App\Repositories\Security\ExternalPassportClientRepository;
use App\Repositories\Security\LocalPassportClientRepository;
use App\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Guards\TokenGuard;
use Laravel\Passport\PassportUserProvider;
use League\OAuth2\Server\ResourceServer;

final class LocalAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClientRepository::class, ExternalPassportClientRepository::class);
    }

    public function boot(): void
    {
        $this->callAfterResolving(HttpKernel::class, static function (HttpKernel $kernel): void {
            $kernel->prependToMiddlewarePriority(AuditLocalAuthentication::class);
        });
        User::observe(LocalSessionUserObserver::class);
        LocalAppUser::observe(LocalSessionUserObserver::class);
        StorageUserDevice::observe(LocalSessionDeviceObserver::class);
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
            [$bucket, $service] = match ($request->route()?->getName()) {
                'v1.auth.refresh' => ['refresh_ip', 'refresh-user'],
                'v1.auth.me' => ['profile_ip', 'current-user'],
                'v1.auth.logout' => ['logout_ip', 'logout-user'],
                'v1.auth.logout-all' => ['logout_ip', 'logout-all-user'],
                default => ['login_ip', 'login-user'],
            };

            return [Limit::perMinute((int) config('local_auth.limits.'.$bucket, 30))
                ->by('local-auth:'.$bucket.':'.$request->ip())
                ->response(static fn (Request $request, array $headers): LocalAuthResponse => new LocalAuthResponse(
                    429, 'Demasiadas solicitudes. Inténtalo más tarde.', null, $service, $headers
                ))];
        });
    }
}
