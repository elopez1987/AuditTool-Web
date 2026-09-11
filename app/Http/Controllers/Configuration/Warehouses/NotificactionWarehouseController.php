<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Warehouse\UpdateWarehouseNotificationsRequest;
use App\Http\Services\Configuration\ServiceSystemWarehouse;
use App\ViewModels\SystemWarehouseViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class NotificactionWarehouseController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemWarehouse $service,
        private readonly SystemWarehouseViewModel $viewModel,
    ) {}

    public function index(string $id)
    {
        $warehouse = $this->service->findOrFail($id);

        $this->authorize('notify', $warehouse);

        $parameters = $this->service->getStatusForSchema('MWH');

        return view(
            'Configuration.SystemWarehouses.Notifications.create',
            $this->viewModel->forNotifications($warehouse, $parameters)
        );
    }

    public function systemwarehousenotifications(UpdateWarehouseNotificationsRequest $request, string $id): RedirectResponse
    {
        $warehouse = $this->service->findOrFail($id);

        $this->authorize('notify', $warehouse);

        try {
            $this->service->updateNotifications($warehouse, $request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccess();
    }
}
