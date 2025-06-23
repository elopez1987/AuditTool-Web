<?php

namespace App\Http\Resources\Receipt\StorageReceiptPeriods;

use App\Models\Receipt\StorageReceiptPeriods\StorageReceiptPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageReceiptPeriod */
class StorageReceiptPeriodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startdate' => $this->startdate,
            'finishdate' => $this->finishdate,
            'auxiliary01' => $this->auxiliary01,
            'auxiliary02' => $this->auxiliary02,
            'auxiliary03' => $this->auxiliary03,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'centerid' => $this->center ? $this->center->description : '',
            'warehouseid' => $this->warehouse ? $this->warehouse->description : '',
            'schemaid' => $this->schema ? $this->schema->name : '',
            'statusid' => $this->status ? $this->status->name : '',
            'useid' => $this->user ? $this->user->name : '',
            
        ];
    }
}
