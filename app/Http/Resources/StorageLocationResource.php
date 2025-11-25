<?php

namespace App\Http\Resources;

use App\Models\Yard\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageLocation */
class StorageLocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'locationid' => $this->locationid,
            'barcode' => $this->barcode,

            //			'configurationflag' => $this->configurationflag,
            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'auxiliary04' => $this->auxiliary04,
            //			'auxiliary05' => $this->auxiliary05,
            //			'schemaid' => $this->schemaid,
            //			'created_at' => $this->created_at,
            //			'updated_at' => $this->updated_at,
            //			'notifications_count' => $this->notifications_count,
            //			'read_notifications_count' => $this->read_notifications_count,
            //			'unread_notifications_count' => $this->unread_notifications_count,
            //			'activities_count' => $this->activities_count,
            //
            //			'centerid' => $this->centerid,
            //			'warehouseid' => $this->warehouseid,
            //			'zoneid' => $this->zoneid,
            //			'statusid' => $this->statusid,
            //
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'zone' => new StorageZoneResource($this->whenLoaded('zone')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'zone' => new StorageZoneResource($this->whenLoaded('zone')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'zone' => new StorageZoneResource($this->whenLoaded('zone')),
        ];
    }
}
