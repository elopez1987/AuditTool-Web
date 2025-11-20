<?php

namespace App\Http\Resources;

use App\Models\Receipt\ReceiptProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReceiptProvider */
class ReceiptProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'id' => $this->id,
            'name' => $this->name,
            'providerid' => $this->providerid,

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

            //			'statusid' => $this->statusid,
            //
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
        ];
    }
}
