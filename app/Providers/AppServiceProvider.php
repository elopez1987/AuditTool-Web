<?php

namespace App\Providers;

use App\Models\Security\Permission\Permission;
use App\Observers\PermissionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

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

        //        $this->registerPolicies();
    }
}
