<?php

namespace App\Http\Resources\SafetyTours;

use App\Models\StorageSafetyTourType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageSafetyTourType */
class StorageSafetyTourTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'typeuuid' => $this->typeuuid,
            'name' => $this->name,
            'value01' => $this->value01,
            'value02' => $this->value02,
            'value03' => $this->value03,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'centerid' => $this->centerid,
            'userid' => $this->userid,
            'schemaid' => $this->schemaid,
            'statusid' => $this->statusid,
        ];
    }
}
