<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Schemas\CreateStatusRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Services\Configuration\SystemStatusService;
use App\ViewModels\Configuration\SystemSchemaStatusViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemStatusController extends BaseController
{
    public function __construct(
        private readonly SystemSchemaStatusViewModel $viewModel,
        private readonly SystemStatusService $statusService,
    ) {}

    public function index($id)
    {
        return view(
            'Configuration.SystemSchemas.createstatus',
            $this->viewModel->get($id)
        );
    }

    public function store(CreateStatusRequest $request): RedirectResponse
    {
        $this->statusService->store($request->validated());

        return $this->redirectBackInfo();
    }

    public function update($id, Request $request)
    {
        $status = SystemStatus::findOrFail($id);
        $status->centerid = $request['centerid'] ?? '';
        $status->warehouseid = $request['warehouseid'] ?? '';
        $status->name = $request['namestatus'];
        $status->comment = $request['comment'] ?? '';
        $status->auxiliary01 = $request['auxiliary01'] ?? '';
        $status->auxiliary02 = $request['auxiliary02'] ?? '';
        $status->auxiliary03 = $request['auxiliary03'] ?? '';
        $status->auxiliary04 = $request['auxiliary04'] ?? '';
        $status->auxiliary05 = $request['auxiliary05'] ?? '';

        $status->save();

        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {
        SystemStatus::destroy($id);

        return $this->redirectBackSuccessMessage('Estatus eliminado con exito.');
    }
}
