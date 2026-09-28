<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentTruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'truckid' => $this->truckid,
            'auxiliary01' => $this->auxiliary01,
            'auxiliary02' => $this->auxiliary02,
        ];
    }
}
