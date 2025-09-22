<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Http\Request;

class SetWarehouseController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $accesos = SecurityAccessWarehouse::where('username', $user->username)->get();
        return view(
            'Configuration.SetWarehouse.index',
            [
                'user' => $user,
                'accesos' => $accesos,
            ]
        );
    }

    public function setwarehousestore(Request $request, $username)
    {
        $accesos = SecurityAccessWarehouse::where('username', $username)
            ->select(['id','defaultlevel'])->get();
        foreach ($accesos as $item) {
            $item->defaultlevel = '';
            $item->save();
        }
        $access = SecurityAccessWarehouse::where('id', $request['defaultlevel'])->first();
        $access->defaultlevel = 'S';
        $access->save();
        return $this->redirectBackSuccessMessage('Bodega fijado con exito.');
    }
}
