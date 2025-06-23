<?php

namespace App\Http\Resources\Yard;

use App\Models\Yard\StorageAreas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageAreas */
class StorageAreasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'centerid' => $this->center->description,
            'warehouseid' => $this->warehouse->name,
            'name' => $this->name,
        ];
    }
}
