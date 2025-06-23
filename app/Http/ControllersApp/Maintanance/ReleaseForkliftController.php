<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintanance\ReleaseForkliftRequest;
use App\Models\Maintenance\StorageLiftTruck;
use App\Models\Maintenance\StorageliftTruckAssigned;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class ReleaseForkliftController extends Controller
{
    use ResponseApi;

    public function clearTruck(ReleaseForkliftRequest $request)
    {
        $truck = $this->searchStorageLiftTruck($request['serialnumber']);
        if (empty($truck)) {
            return $this->getResponseWithErrors('clearTruck', 'El equipo no existe favor de validar el numero de serie.');
        }
        if ($truck->statusid == 'MT-B') {
            return $this->getResponseWithErrors('clearTruck',
                'El equipo se encuentra bloquedo, favor de notificar a mantenimiento');
        }
        $truck->statusid = 'MT-A';
        $truck->save();
//     Terminar la asignacion
        $assignedTruck = $this->searchStorageliftTruckAssigned($request['serialnumber']);
        if (!empty($assignedTruck)) {
            $assignedTruck->statusid = 'MT-A';
            $assignedTruck->finishdate = Carbon::now();
            $assignedTruck->save();
        }
        return $this->getResponseWithSuccess('clearTruck', 'Equipo liberado con exito.');
    }


    public function searchStorageLiftTruck($serialnumber)
    {
        return StorageLiftTruck::controlAccessUser()->where('serialnumber', '=', $serialnumber)->first();
    }

    public function searchStorageliftTruckAssigned($serialnumber)
    {
        return StorageliftTruckAssigned::where('serialnumber', '=', $serialnumber)->where('statusid', '=', 'MT-C')->first();
    }
}
