<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Users\AccessOriginRequest;
use App\Services\Security\AccessOriginService;
use App\Services\Security\SecurityUserService;
use App\Services\Yard\StorageUnitOriginService;
use App\ViewModels\Yard\AccessOriginUser\AccessOriginViewModel;

class AccessOriginController extends BaseController
{
    public function __construct(
        private readonly AccessOriginService $originService,
        private readonly SecurityUserService $userService,
        private readonly StorageUnitOriginService $unitOriginService,
        private readonly AccessOriginViewModel $viewModel,
    ) {}

    public function index($uuiduser)
    {
        $this->authorize('securityusers.accessorigin');

        $user = $this->userService->findByUuid($uuiduser);

        $origins = $this->unitOriginService->searchTypeForUser($user->username);

        $originsAssigned = $this->originService->getOrigingForUser($user->username);

        return view('Security.Users.AccessOrigin.index',
            $this->viewModel->forIndex($origins, $originsAssigned, $user)
        );
    }

    public function update(AccessOriginRequest $request)
    {
        $this->originService->store($request->validated());

        return $this->redirectBackSuccessMessage('Proveedor asignado correctamente');
    }

    public function destroy($id)
    {
        $this->originService->destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con exito');
    }
}
