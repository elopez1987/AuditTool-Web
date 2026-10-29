<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'CenterId' => $this->centerid,
            'WarehouseId' => $this->warehouseid,
            /** @var int */
            'Id' => $this->id,
            'Name' => $this->name,
            'RouteId' => $this->providerid,
        ];
    }
}
