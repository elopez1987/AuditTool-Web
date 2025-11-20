<?php

namespace App\Http\Resources;

use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageZone */
class StorageZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'zoneid' => $this->zoneid,
            'name' => $this->name,

            //			'configurationflag' => $this->configurationflag,
            //			'capacity' => $this->capacity,
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
            //			'warehouseid' => $this->warehouseid,
            //			'statusid' => $this->statusid,
            //
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
        ];
    }
}
