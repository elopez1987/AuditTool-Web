<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $namespaceApp = 'App\Http\ControllersApp';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

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
        $this->mapApiRoutes();
        $this->mapApiAppRoutes();

        $this->mapWebRoutes();
        $this->mapConfigurationRoutes();
        $this->mapSecurityRoutes();
        $this->mapSelectRoutes();
        $this->mapShipmentsRoutes();
        $this->mapYardsRoutes();
        $this->mapReceiptsRoutes();
        $this->mapEvidencesRoutes();
        $this->mapInventoryRoutes();
        $this->mapReportsRoutes();
        $this->mapCountingRoutes();
        $this->mapMaintenanceRoutes();
        $this->mapToursRoutes();


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

    protected function mapCountingRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Counting.php'));
    }

    protected function mapYardsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Yards.php'));
    }

    protected function mapEvidencesRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Evidences.php'));
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

    protected function mapApiAppRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespaceApp)
            ->group(base_path('routes/apiapp.php'));
    }
}
