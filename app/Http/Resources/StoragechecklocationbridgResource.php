<?php

namespace App\Http\Resources;

use App\Http\Resources\Shipments\OrderDeliveryResource;
use App\Models\Shipments\Storagechecklocationbridg;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Storagechecklocationbridg */
class StoragechecklocationbridgResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workday' => $this->workday,
            'turnid' => $this->turnid,
            'locationid' => $this->locationid,
            'complemento' => $this->complemento,
            'totalIzquierdo' => $this->totalIzquierdo,
            'totalderecho' => $this->totalderecho,
            'totalfinal' => $this->totalfinal,
            'detalleIzquierdo' => $this->detalleIzquierdo,
            'detalleDerecho' => $this->detalleDerecho,
            'name' => $this->name,
            'roleid' => $this->roleid,
            'namesupervisor' => $this->namesupervisor,
            'keysupervisor' => $this->keysupervisor,
            'value01' => $this->value01,
            'value02' => $this->value02,
            'value03' => $this->value03,

            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'orderid' => $this->orderid,

            'orderid' => new OrderDeliveryResource($this->whenLoaded('orderid')),
        ];
    }
}
