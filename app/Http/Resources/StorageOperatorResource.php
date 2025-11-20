<?php

namespace App\Http\Resources;

use App\Models\Yard\StorageOperator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageOperator */
class StorageOperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'barcode' => $this->barcode,
            'name' => $this->name,
            'drivernumber' => $this->drivernumber,
            'phone' => $this->phone,

            //			'configurationflag' => $this->configurationflag,
            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'auxiliary04' => $this->auxiliary04,
            //			'auxiliary05' => $this->auxiliary05,
            //			'schemaid' => $this->schemaid,
            //			'created_at' => $this->created_at,
            //			'updated_at' => $this->updated_at,
            //			'activities_count' => $this->activities_count,
            //
            //			'centerid' => $this->centerid,
            //			'statusid' => $this->statusid,
            //			'carrierid' => $this->carrierid,
            //			'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            //			'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            //			'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
        ];
    }
}
