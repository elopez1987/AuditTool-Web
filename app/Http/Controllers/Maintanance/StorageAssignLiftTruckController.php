<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Maintenance\StorageAssignLiftTrucks;
use App\Traits\Web\Maintenance\QueryStorageLiftTrucks;
use Illuminate\Http\Request;

class StorageAssignLiftTruckController extends BaseController
{

    use QueryStorageLiftTrucks;

    public function index()
    {
        $this->authorize('storageassignlifttrucks.index');
        $types = $this->getStatusForSchema('TPM');
        return view('Maintenance.StorageAssignLiftTrucks.index',
            compact('types'));
    }

    public function edit($id)
    {
        $this->authorize('storageassignlifttrucks.edit');
        $centerid = $this->getCenterForUser();
        $type = SystemStatus::findOrFail($id);
        $users = $this->getUserForAssign($centerid, $id);
        $userAssigns =   $this->getStorageAssignLiftTrucks($id);
        return view('Maintenance.StorageAssignLiftTrucks.edit',
            compact('type', 'users','userAssigns'));
    }

    public function update(Request $request, $id)
    {
        $centerid = $this->getCenterForUser();
        if ($request['username'] != "") {
            $total = count($request["username"]) - 1;
            for ($i = 0; $i <= $total; $i++) {
                StorageAssignLiftTrucks::create([
                    'centerid' => $centerid,
                    'username' => $request['username'][$i],
                    'typeid' => $id,
                ]);
            }
        }
        return $this->redirectBackSuccessMessage('Usuario agregados con exito.');
    }

    public function deleteUserAssingTruck(Request $request)
    {
        StorageAssignLiftTrucks::destroy($request['id']);
        return response()->json();
    }




}
