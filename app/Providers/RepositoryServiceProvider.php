<?php

namespace App\Providers;

use App\Repositories\Eloquent\SecurityAccessWarehouseRepository;
use App\Repositories\Eloquent\SystemCenterDistributionRepository;
use App\Repositories\Eloquent\SystemStatusRepository;
use App\Repositories\Eloquent\SystemWarehouseRepository;
use App\Repositories\SecurityAccessWarehouseRepositoryInterface;
use App\Repositories\SystemCenterDistributionRepositoryInterface;
use App\Repositories\SystemStatusRepositoryInterface;
use App\Repositories\SystemWarehouseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * The repository interface -> implementation bindings for the application.
     *
     * Named differently from Laravel's magic `$bindings`/`$singletons`
     * provider properties (Illuminate\Foundation\Application::register())
     * so this stays an explicit, self-contained registration.
     *
     * @var array<class-string, class-string>
     */
    protected array $repositoryBindings = [
        SystemCenterDistributionRepositoryInterface::class => SystemCenterDistributionRepository::class,
        SystemStatusRepositoryInterface::class => SystemStatusRepository::class,
        SystemWarehouseRepositoryInterface::class => SystemWarehouseRepository::class,
        SecurityAccessWarehouseRepositoryInterface::class => SecurityAccessWarehouseRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositoryBindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
