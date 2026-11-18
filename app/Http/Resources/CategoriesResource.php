<?php

namespace App\Http\Resources;

use App\Models\Receipt\StorageCategoryIncidence;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin StorageCategoryIncidence
 */
class CategoriesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'CenterId' => $this->centerid,
            'CategoryId' => $this->id,
            'CategoryName' => $this->description,
        ];
    }
}
