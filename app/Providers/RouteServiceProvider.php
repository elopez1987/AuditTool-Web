<?php

namespace App\Providers;

use Auth0\Laravel\Controllers\CallbackController;
use Auth0\Laravel\Controllers\LoginController;
use Auth0\Laravel\Controllers\LogoutController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     */
    public const HOME = '/home';

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAuth0Routes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapConfigurationRoutes();
        $this->mapSecurityRoutes();
        $this->mapSelectRoutes();
        $this->mapShipmentsRoutes();
        $this->mapYardsRoutes();
        $this->mapReceiptsRoutes();
        $this->mapInventoryRoutes();
        $this->mapReportsRoutes();
        $this->mapMaintenanceRoutes();
        $this->mapToursRoutes();
        $this->mapEvidencesRoutes();
    }

    protected function mapAuth0Routes()
    {
        Route::middleware('web')->group(function () {
            Route::get(config('auth0.routes.login', '/login'), LoginController::class)->name('login');
            Route::get(config('auth0.routes.logout', '/logout'), LogoutController::class)->name('logout');
            Route::match(['GET', 'POST'], config('auth0.routes.callback', '/callback'), CallbackController::class)->name('callback');
        });
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapMaintenanceRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Maintenance.php'));
    }

    protected function mapToursRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Tours.php'));
    }

    protected function mapInventoryRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Inventory.php'));
    }

    protected function mapYardsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Yards.php'));
    }

    protected function mapReceiptsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Receipt.php'));
    }

    protected function mapReportsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Reports.php'));
    }

    protected function mapShipmentsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Shipments.php'));
    }

    protected function mapConfigurationRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Configuracion.php'));
    }

    protected function mapSecurityRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Security.php'));
    }

    protected function mapEvidencesRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Evidences.php'));
    }

    protected function mapSelectRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Select.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
