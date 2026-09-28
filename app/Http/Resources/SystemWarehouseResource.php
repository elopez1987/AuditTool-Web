<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemWarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'center' => $this->center,
            'warehouseid' => $this->warehouseid,
            'warehousename' => $this->name,
        ];
    }
}
