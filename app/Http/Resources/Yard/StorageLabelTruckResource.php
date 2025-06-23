<?php

namespace App\Http\Resources\Yard;

use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageLabelTruck */
class StorageLabelTruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'carrierid' => $this->carrierid,
            'barcode' => $this->barcode,
            'boxnumber' => $this->boxnumber,
            'econumber' => $this->econumber,
            'typeid' => $this->typeid,
            'truckid' => $this->truckid,
            'statusid' => $this->statusid,
        ];
    }
}
