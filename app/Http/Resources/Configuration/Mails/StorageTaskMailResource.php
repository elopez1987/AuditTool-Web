<?php

namespace App\Http\Resources\Configuration\Mails;

use App\Models\Configuration\Mails\StorageTaskMail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageTaskMail */
class StorageTaskMailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'modeltype' => $this->modeltype,
            'modelid' => $this->modelid,
            'mailid' => $this->mailid,
            'value01' => $this->value01,
            'value02' => $this->value02,
            'value03' => $this->value03,
            'value04' => $this->value04,
            'value05' => $this->value05,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'schemaid' => $this->schemaid,
            'statusid' => $this->statusid,
        ];
    }
}
