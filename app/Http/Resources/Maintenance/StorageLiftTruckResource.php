<?php

namespace App\Http\Resources\Maintenance;

use App\Models\Maintenance\StorageLiftTruck;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin StorageLiftTruck */
class StorageLiftTruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'serialnumber' => $this->serialnumber,
            'numbertruck' => $this->numbertruck,
            'model' => $this->model ? $this->model->name : '',
            'type' => $this->type ? $this->type->name : '',
            'createuser' => $this->user ? $this->user->name : '',
            'schemabrandid' => $this->brand ? $this->brand->name : '',
        ];
    }
}
