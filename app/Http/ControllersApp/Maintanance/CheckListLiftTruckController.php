<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Maintenance\StorageLiftTruckResource;
use App\Models\Maintenance\StorageLiftTruck;
use App\Models\Maintenance\StorageliftTruckAssigned;
use App\Traits\ResponseApi;
use Auth;
use Illuminate\Http\Request;

class CheckListLiftTruckController extends Controller
{

    use ResponseApi;

    public function getStatusAssignedTruck()
    {
        $assignedUser = StorageliftTruckAssigned::controlAccessUser()->where('username', '=', Auth::id())
            ->where('statusid', '=', 'MT-C')
            ->whereNull('finishdate')->first();
        if (!empty($assignedUser)) {
            return $this->getResponse('getStatusAssignedTruck', '1', 'Tienes asignado un equipo.',
                [
                    'serialnumber' => $assignedUser->serialnumber,
                    'startdate' => $assignedUser->startdate
                ],
                '200'
            );
        } else {
            return $this->getResponse('getStatusAssignedTruck', '0', 'No tiene asignado equipo.',
                [],
                '200'
            );
        }
    }


//    Consultar el equipo
    public function getConsultForkLiftEquipment(Request $request)
    {
        if (empty($request['serialnumber'])) {
            return $this->getResponseWithErrors('getConsultForkLiftEquipment', 'El número de serie es obligatorio.');
        }
        $data = StorageLiftTruck::controlAccessUser()->where('serialnumber', '=', $request['serialnumber'])->first();
        if (empty($data)) {
            return $this->getResponseWithErrors('getConsultForkLiftEquipment', 'El equipo no existe.');
        }
        return $this->showOne('getConsultForkLiftEquipment', new StorageLiftTruckResource($data));
    }


}
