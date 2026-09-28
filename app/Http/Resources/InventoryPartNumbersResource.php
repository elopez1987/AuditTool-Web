<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryPartNumbersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prtnum' => $this->prtnum,
            'lngdsc' => $this->lngdsc,
            'untcas' => $this->untcas,
            'palhgt' => $this->palhgt,
            'abccod' => $this->abccod,
            'shelflife' => $this->shelflife,
            'untpal' => $this->untpal,
        ];
    }
}
