<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageLocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /** @var int */
            'Id' => $this->id,
            'Name' => $this->name,
            /** @var string|null */
            'LocationId' => $this->locationid,
            /** @var string|null */
            'BarCode' => $this->barcode,
        ];
    }
}
