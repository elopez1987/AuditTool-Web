<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'ZoneId' => $this->zoneid,
            'Name' => $this->name,
        ];
    }
}
