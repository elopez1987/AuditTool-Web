<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintanance\UnassignEquipmentRequest;
use App\Models\Maintenance\StorageLiftTruck;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnassignEquipmentToUserController extends Controller
{
    use ResponseApi;

    //    Liberar equipos asignados
    public function getTruckAssignToUser()
    {
        $data = StorageLiftTruck::controlAccessUser()
            ->where('statusid', '=', 'MT-C')
            ->select(['centerid', 'serialnumber', 'numbertruck', 'updated_at'])->get();
        return $this->getResponseSuccess('getLiftTruckLocked', $data);
    }

    public function updateStatusToTruckAssign(UnassignEquipmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $truck = StorageLiftTruck::controlAccessUser()->where('serialnumber', '=', $request['serialnumber'])->first();
            $truck->statusid = 'MT-A';
            $truck->save();
            $this->UpdateEquipment($request['serialnumber']);
            DB::commit();
            $success = true;
        } catch (Exception) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseWithSuccess('updateStatusToTruckAssign', 'Equipo liberado con exito.');
        } else {
            return $this->getResponseWithErrors('updateStatusToTruckAssign', 'Error al procesar la solicitud, intente mas tarde.');
        }
    }

    /**
     * @param $serialnumber
     * @return void
     */
    public function UpdateEquipment($serialnumber): void
    {
        DB::table('storagelifttruckassigned')
            ->where('serialnumber', '=', $serialnumber)
            ->where('statusid', 'MT-C')
            ->whereNull('finishdate')
            ->update([
                'auxiliary01' => Auth::user()->username,
                'finishdate' => Carbon::now(),
                'statusid' => 'MT-A'
            ]);
    }


}
