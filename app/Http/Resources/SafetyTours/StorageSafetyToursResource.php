<?php

namespace App\Http\Resources\SafetyTours;

use App\Models\SafetyTours\StorageSafetyTours;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageSafetyTours */
class StorageSafetyToursResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'centername' => $this->center->description,
            'name' => $this->name,
            'datetour' => $this->datetours,
        ];
    }
}
