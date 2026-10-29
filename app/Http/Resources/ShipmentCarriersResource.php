<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentCarriersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Name' => $this->name,
            'CarrierId' => $this->carrierid,
        ];
    }
}
