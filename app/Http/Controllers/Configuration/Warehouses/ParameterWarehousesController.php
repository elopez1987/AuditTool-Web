<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Warehouse\UpdateWarehouseConfigurationFlagRequest;
use App\Http\Services\Configuration\ServiceSystemWarehouse;
use App\ViewModels\SystemWarehouseViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ParameterWarehousesController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemWarehouse $service,
        private readonly SystemWarehouseViewModel $viewModel,
    ) {}

    public function parameters(string $id)
    {
        $warehouse = $this->service->findOrFail($id);

        $this->authorize('parametrize', $warehouse);

        $parameters = $this->service->getStatusForSchema('SWH');

        return view(
            'Configuration.SystemWarehouses.Parameters.create',
            $this->viewModel->forParameters($warehouse, $parameters)
        );
    }

    public function systemwarehouseparameters(UpdateWarehouseConfigurationFlagRequest $request, string $id): RedirectResponse
    {
        $warehouse = $this->service->findOrFail($id);

        $this->authorize('parametrize', $warehouse);

        try {
            $this->service->updateConfigurationFlag($warehouse, $request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccess();
    }
}
