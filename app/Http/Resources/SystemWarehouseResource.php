<?php

namespace App\Http\Resources;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SystemWarehouse */
class SystemWarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'center' => $this->center,
            'warehouseid' => $this->warehouseid,
            'warehousename' => $this->name,

            //			'warehouse' => $this->warehouse,
            //			'configurationflag' => $this->configurationflag,
            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'auxiliary04' => $this->auxiliary04,
            //			'auxiliary05' => $this->auxiliary05,
            //			'auxiliary06' => $this->auxiliary06,
            //			'auxiliary07' => $this->auxiliary07,
            //			'auxiliary08' => $this->auxiliary08,
            //			'auxiliary09' => $this->auxiliary09,
            //			'auxiliary10' => $this->auxiliary10,
            //			'schemaid' => $this->schemaid,
            //			'created_at' => $this->created_at,
            //			'updated_at' => $this->updated_at,
            //
            //			'centerid' => $this->centerid,
            //			'statusid' => $this->statusid,
        ];
    }
}
