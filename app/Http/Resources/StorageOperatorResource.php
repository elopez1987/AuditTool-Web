<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageOperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /** @var int */
            'id' => $this->id,
            /** @var string|null */
            'barcode' => $this->barcode,
            'name' => $this->name,
            //            'drivernumber' => $this->drivernumber,
            //            'phone' => $this->phone,
        ];
    }
}
