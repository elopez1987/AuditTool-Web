<?php

namespace App\Providers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Policies\Configuration\SystemCenterDistributionPolicy;
use App\Policies\Configuration\SystemWarehousePolicy;
use App\Services\Security\MobileAuthConfiguration;
use Auth0\Laravel\Entities\InstanceEntity;
use Auth0\Laravel\Guards\AuthorizationGuard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        SystemCenterDistribution::class => SystemCenterDistributionPolicy::class,
        SystemWarehouse::class => SystemWarehousePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('auth0-mobile', static function (Application $app, string $name, array $config): AuthorizationGuard {
            return new AuthorizationGuard(
                $name,
                $config,
                InstanceEntity::create($app->make(MobileAuthConfiguration::class)->sdkOptions())
            );
        });
        RateLimiter::for('mobile-auth', static fn (Request $request): Limit => Limit::perMinute(60)
            ->by($request->ip())
            ->response(static fn (Request $request, array $headers): JsonResponse => new JsonResponse([
                'error' => 'TOO_MANY_REQUESTS',
                'message' => 'Demasiadas solicitudes. Inténtalo más tarde.',
            ], 429, $headers + ['Cache-Control' => 'no-store, private'])));

        Gate::before(function ($user) {
            return $user->hasRole('ADMINISTRADOR') ? true : null;
        });

        //        Passport::routes();
        //        Passport::tokensExpireIn(Carbon::now()->addSeconds(10));
        //        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
