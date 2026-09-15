<?php

namespace App\Providers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Policies\Configuration\SystemCenterDistributionPolicy;
use App\Policies\Configuration\SystemWarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        Gate::before(function ($user) {
            return $user->hasRole('ADMINISTRADOR') ? true : null;
        });

        //        Passport::routes();
        //        Passport::tokensExpireIn(Carbon::now()->addSeconds(10));
        //        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
