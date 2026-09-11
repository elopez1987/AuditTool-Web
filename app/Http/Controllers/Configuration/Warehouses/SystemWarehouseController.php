<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Warehouse\IndexWarehouseRequest;
use App\Http\Requests\Web\Configuration\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Web\Configuration\Warehouse\UpdateWarehouseRequest;
use App\Http\Services\Configuration\ServiceSystemCenterDistribution;
use App\Http\Services\Configuration\ServiceSystemWarehouse;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\ViewModels\SystemWarehouseViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class SystemWarehouseController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemWarehouse $service,
        private readonly SystemWarehouseViewModel $viewModel,
        private readonly ServiceSystemCenterDistribution $centerDistribution,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', SystemWarehouse::class);

        return view('Configuration.SystemWarehouses.index');
    }

    public function getDataWarehouses(IndexWarehouseRequest $request)
    {
        $data = $this->service->search($request->validated('name'));

        return $this->showDataToView($data, 'Configuration.SystemWarehouses.partials.buttons');
    }

    public function create()
    {
        $this->authorize('create', SystemWarehouse::class);

        $centers = $this->centerDistribution->getCenterAll();

        return view('Configuration.SystemWarehouses.create', $this->viewModel->forCreate($centers));
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $this->authorize('create', SystemWarehouse::class);

        try {
            $this->service->store($request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse('systemwarehouses.index');
    }

    public function edit(SystemWarehouse $systemwarehouse)
    {
        $this->authorize('update', $systemwarehouse);

        $status = $this->service->getStatusForSchema('STS');

        return view(
            'Configuration.SystemWarehouses.edit',
            $this->viewModel->forEdit($systemwarehouse, $status),
        );
    }

    public function update(UpdateWarehouseRequest $request, SystemWarehouse $systemwarehouse): RedirectResponse
    {
        $this->authorize('update', $systemwarehouse);

        try {
            $this->service->update($systemwarehouse, $request->validated(), $request);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->infoResponse('systemwarehouses.index');
    }
}
