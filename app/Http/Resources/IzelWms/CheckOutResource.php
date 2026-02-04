<?php

namespace App\Http\Resources\IzelWms;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReceiptUnit */
class CheckOutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'centerid' => $this->warehouse ? $this->warehouse->center : '',
            'warehouseid' => $this->warehouse ? $this->warehouse->warehouse : '',
            'folionumber' => $this->id,
            'outputdate' => $this->outputdate,
        ];
    }
}
