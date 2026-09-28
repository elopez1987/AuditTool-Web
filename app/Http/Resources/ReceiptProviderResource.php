<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'id' => $this->id,
            'name' => $this->name,
            'providerid' => $this->providerid,
        ];
    }
}
