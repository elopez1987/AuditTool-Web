<?php

namespace App\Providers;

use App\Models\Receipt\ReceiptAsn;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Permission\Permission;
use App\Observers\Receipt\ReceiptAsnObserver;
use App\Observers\Security\PermissionObserver;
use App\Observers\Security\SecurityFunctionObserver;
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
        //
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
