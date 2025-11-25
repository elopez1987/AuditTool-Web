<?php

namespace App\Http\Resources;

use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ShipmentCarrier */
class ShipmentCarriersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'carrierid' => $this->carrierid,

            //			'configurationflag' => $this->configurationflag,
            //			'rfc' => $this->rfc,
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
        ];
    }
}
