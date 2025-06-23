<?php

namespace App\Http\Resources\Inventory;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InventoryPartnumber */
class InventoryPartNumberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'partnumberid' => $this->prtnum,
            'description' => $this->lngdsc,
            'category01id' => $this->category01id,
            'category01' => $this->category01 ? $this->category01->description : '',
            'category02id' => $this->category02id,
            'category02' => $this->category02 ? $this->category02->description : '',
            'stkuom' => $this->stkuom,
            'stuatusname' => $this->status ? $this->status->name : '',
        ];
    }
}
