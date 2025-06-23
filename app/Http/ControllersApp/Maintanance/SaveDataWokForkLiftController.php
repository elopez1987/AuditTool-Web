<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StorageUsageDataForkLiftRequest;
use App\Models\Maintenance\StorageLiftTruck;
use App\Models\Maintenance\StorageUsageDataForkLift;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\ResponseApi;

class SaveDataWokForkLiftController extends Controller
{
    use ResponseApi;
    use SavePhotoInRegister;


    public function index(StorageUsageDataForkLiftRequest $request)
    {
        \Log::info($request->all());
        if (empty($request['serialnumber'])) {
            return $this->getResponseWithErrors('getConsultForkLiftEquipment', 'El número de serie es obligatorio.');
        }
        $data = StorageLiftTruck::controlAccessUser()->where('serialnumber', '=', $request['serialnumber'])->first();
        if (empty($data)) {
            return $this->getResponseWithErrors('getConsultForkLiftEquipment', 'El equipo no existe.');
        }
        $path = $this->createNewEvidenceForRegister($request['value01'], $data, 'Maintenance', 'CheckList', 'Evidence');
         StorageUsageDataForkLift::create(
             $request->validated() +
             ['centerid' => $this->getCenterForUserForApi()] +
         ['value01' => $path]);
        return $this->getResponseWithSuccess('getConsultForkLiftEquipment', 'Registro guardado con exito.');
    }


}
