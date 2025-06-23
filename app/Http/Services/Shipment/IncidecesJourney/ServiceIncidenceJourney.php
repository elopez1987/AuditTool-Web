<?php

namespace App\Http\Services\Shipment\IncidecesJourney;

use App\Models\General\StorageComment;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Traits\GetParameters;
use Auth;


class ServiceIncidenceJourney
{
    use GetParameters;

    public function addNewIncidence($shipment)
    {
        $incidence = StorageIncidenceJourney::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'carrierid' => $shipment->carrierid,
        ]);
        return $incidence;
    }


    public function addDetailToIncidence($request, $incidence)
    {
        StorageIncidenceJourneyDetails::create([
            'centerid' => $incidence->centerid,
            'warehouseid' => $incidence->warehouseid,
            'orderid' => $incidence->orderid,
            'delivery' => $request['delivery'],
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'unitmeasure' => $request['unitmeasure'],
            'razoncode' => $request['razoncode'],
            'downloaddate' => $request['downloaddate'],
            'auxiliary03' => Auth::user()->username,
            'partnumberiderror' => $request['partnumberiderror'],
        ]);
        $this->createNewCommentForIncidence($incidence, $request);
    }


    public function createNewCommentForIncidence($incidence, $request)
    {
        if (!empty($request['comment'])) {
            StorageComment::createCommentary('StorageIncidenceJourney',$incidence->folioid,$request);
        }
    }


}
