<?php

namespace App\Http\Resources;

use App\Models\Receipt\StorageSchedulleReceipt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageSchedulleReceipt */
class StorageschedullereceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workday' => $this->workday,
            'value01' => $this->value01,
            'value02' => $this->value02,
            'value03' => $this->value03,
            'value04' => $this->value04,
            'value05' => $this->value05,
            'schemaid' => $this->schemaid,
            'statusid' => $this->statusid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'turnid' => $this->turnid,
            'secuence' => $this->secuence,
            'receiptid' => $this->receiptid,
        ];
    }
}
