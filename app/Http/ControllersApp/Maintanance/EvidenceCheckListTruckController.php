<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintanance\EvidenceCheckLisRequest;
use App\Models\Maintenance\CheckListLiftTrucks;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;

class EvidenceCheckListTruckController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;



    public function saveEvidenceCheckListTruck(EvidenceCheckLisRequest $request)
    {
        $model = CheckListLiftTrucks::controlAccessUser()->where('id', $request['checklistid'])->first();
        $this->createNewEvidence($request['file'], $model,'EVI','StorageLiftTruck','Maintenance','Truck',$request);
        return $this->getResponseWithSuccess('saveEvidenceCheckListTruck', 'Evidencia agregado con exito.');
    }




}
