<?php

namespace App\Http\Resources;

use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageLabelTruck */
class StorageLabelTruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'barcode' => $this->barcode,
            'boxnumber' => $this->boxnumber,
            'econumber' => $this->econumber,
            'typeid' => $this->typeid,
            'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            'truck' => new ShipmentTruckResource($this->whenLoaded('truck')),

            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'schemaid' => $this->schemaid,
            //			'created_at' => $this->created_at,
            //			'updated_at' => $this->updated_at,
            //			'activities_count' => $this->activities_count,
            //
            //			'centerid' => $this->centerid,
            //			'warehouseid' => $this->warehouseid,
            //			'carrierid' => $this->carrierid,
            //			'truckid' => $this->truckid,
            //			'statusid' => $this->statusid,
            //
            //			'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
            //			'carrier' => new ShipmentCarriersResource($this->whenLoaded('carrier')),
            //			'warehouse' => new SystemWarehouseResource($this->whenLoaded('warehouse')),
        ];
    }
}
