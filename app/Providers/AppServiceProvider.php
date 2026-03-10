<?php

namespace App\Providers;

use App\Models\Receipt\ReceiptAsn;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Permission\Permission;
use App\Observers\PermissionObserver;
use App\Observers\ReceiptAsnObserver;
use App\Observers\SecurityFunctionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('America/Mexico_City');

        // Register observers
        Permission::observe(PermissionObserver::class);
        SecurityFunction::observe(SecurityFunctionObserver::class);
        ReceiptAsn::observe(ReceiptAsnObserver::class);

        //        $this->registerPolicies();

    }
}
