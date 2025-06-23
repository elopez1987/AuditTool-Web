<?php

namespace App\Http\Resources\SafetyTours;

use App\Models\SafetyTours\StorageSafetyTourDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageSafetyTourDetail */
class StorageSafetyTourDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'centername' => $this->center ? $this->center->description : '',
            'tourid' => $this->tourid,
            'tagid' => $this->tag ? $this->tag->tagid : '',
            'secuence' => $this->secuence,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'comment' => $this->comment,
            'statusid' => $this->statusid,
            'statusname' => $this->status ? $this->status->name : '',
            'categoria' => $this->categorytype ? $this->categorytype->name : '',
        ];
    }
}
