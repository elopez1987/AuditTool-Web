<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Http\Services\Security\ServiceSecurityAccessWarehouse;
use App\ViewModels\SecurityAccessWarehouseViewModel;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class SetWarehouseController extends BaseController
{
    public function __construct(
        private readonly ServiceSecurityAccessWarehouse $service,
        private readonly SecurityAccessWarehouseViewModel $viewModel,
    ) {}

    public function index()
    {
        $user = Auth::user();
        $accesos = $this->service->getAccessesForUser($user->username);

        return view('Configuration.SetWarehouse.index', $this->viewModel->forIndex($user, $accesos));
    }

    public function setwarehousestore(Request $request, string $username): RedirectResponse
    {
        // Autoservicio: cada usuario solo puede fijar su propia bodega por
        // defecto (antes, cualquier usuario autenticado podía fijarla para
        // otro cambiando el username en la URL).
        abort_if($username !== Auth::user()->username, 403);

        try {
            $this->service->setDefaultWarehouse($username, (int) $request->input('defaultlevel'));
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccessMessage('Bodega fijado con exito.');
    }
}
