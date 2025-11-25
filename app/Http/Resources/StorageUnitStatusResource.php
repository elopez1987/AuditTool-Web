<?php

namespace App\Http\Resources;

use App\Models\Yard\StorageUnitStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageUnitStatus */
class StorageUnitStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unitstatusid' => $this->unitstatusid,
            'auxiliary01' => $this->auxiliary01,

            //			'configurationflag' => $this->configurationflag,
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
        ];
    }
}
