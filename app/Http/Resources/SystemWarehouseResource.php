<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemWarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Center' => $this->center,
            'WarehouseId' => $this->warehouseid,
            'WarehouseName' => $this->name,
        ];
    }
}
