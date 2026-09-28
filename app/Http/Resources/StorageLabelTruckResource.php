<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageLabelTruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'barcode' => $this->barcode,
            'boxnumber' => $this->boxnumber,
            'econumber' => $this->econumber,
            'typeid' => $this->typeid,
            'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            'truck' => new ShipmentTruckResource($this->whenLoaded('truck')),
        ];
    }
}
