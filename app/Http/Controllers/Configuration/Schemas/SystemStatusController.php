<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Schemas\CreateStatusRequest;
use App\Http\Requests\Web\Configuration\Schemas\UpdateStatusRequest;
use App\Services\Configuration\SystemStatusService;
use App\ViewModels\Configuration\SystemSchemaStatusViewModel;
use Illuminate\Http\RedirectResponse;

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
            $this->viewModel->get($id),
        );
    }

    public function store(CreateStatusRequest $request): RedirectResponse
    {
        $this->statusService->store($request->validated());

        return $this->redirectBackInfo();
    }

    public function update(UpdateStatusRequest $request)
    {
        $this->statusService->update($request->validated());

        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {

        $this->statusService->destroy($id);

        return $this->redirectBackSuccessMessage('Estatus eliminado con exito.');
    }
}
