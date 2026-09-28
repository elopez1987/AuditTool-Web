<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'id' => $this->id,
            'folionumber' => $this->id,
            'barcode' => $this->barcode,
            'shipmentid' => $this->shipmentid,
            'boxnumber' => $this->boxnumber,
            'econumberbox' => $this->econumber,
            'containerseal' => $this->containerseal,
            'statusname' => $this->status->name,
            'zoneorg' => $this->zone->name,
            'locationorg' => $this->location->name,
            'carriername' => $this->carrier->name,
            'operatorname' => $this->tracto ? $this->tracto->operatorname : '',
            'barcodetruck' => $this->tracto ? $this->tracto->barcode : '',
            'trucknumber' => $this->tracto ? $this->tracto->trucknumber : '',
            'phone' => $this->tracto ? $this->tracto->phone : '',
            'foliotruck' => $this->tracto ? $this->tracto->id : '',
            'razonname' => $this->reason->name,
            'unitstatusname' => $this->unitstatus ? $this->unitstatus->name : '',

        ];
    }
}
