<?php

namespace App\Http\Services\Shipment\IncidecesJourney;

use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageKeyIncidenceOperation;
use App\Models\Shipments\StorageKeyIncidenceTransport;
use Carbon\Carbon;

class ServiceResponseIncidences
{

    public function createKeyTransport($incidence)
    {
        $incidence->statuskeytrid = 'RF';
        $incidence->save();
        $data = StorageKeyIncidenceOperation::controlAccessUser()
            ->where('orderid', '=', $incidence->orderid)->get();
        foreach ($data as $item) {
            StorageKeyIncidenceTransport::updateOrCreate([
                'centerid' => $item->centerid,
                'warehouseid' => $item->warehouseid,
                'orderid' => $item->orderid,
                'delivery' => $item->delivery,
                'partnumberid' => $item->partnumberid
            ]);
        }
    }

    public function updateStatusIncidence($folioid, $statusid)
    {
        $incidence = StorageIncidenceJourney::where('folioid', $folioid)->first();
        $incidence->statusid = $statusid;
        $incidence->responsedate = Carbon::now();
        $incidence->save();
    }

}
