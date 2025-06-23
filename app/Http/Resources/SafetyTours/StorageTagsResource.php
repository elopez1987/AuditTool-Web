<?php

namespace App\Http\Resources\SafetyTours;

use App\Models\SafetyTours\StorageTags;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageTags */
class StorageTagsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'centername' => $this->center->description,
            'id' => $this->id,
            'tagid' => $this->tagid,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'username' => $this->user->name,
            'statusname' => $this->status->name,
            'created' => $this->created_at
        ];
    }
}
