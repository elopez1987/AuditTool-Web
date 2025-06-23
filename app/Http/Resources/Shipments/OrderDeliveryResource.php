<?php

namespace App\Http\Resources\Shipments;

use App\Models\Shipments\OrderDeliverie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderDeliverie */
class OrderDeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'turnid' => $this->turnid,
            'carrierid' => $this->carrierid,
            'customerid' => $this->customerid,
            'shipmenttypeid' => $this->shipmenttypeid,
            'statusid' => $this->statusid,

            'warehousename' => $this->warehouse ? $this->warehouse->name : '',
            'city' => $this->customer ? $this->customer->city : '',
            'customername' => $this->customer ? $this->customer->name : '',
            'carriername' => $this->carrier ? $this->carrier->name : '',
            'shipmentid' => $this->shipmentid,
            'workday' => $this->workday,
            'loadingdate' => $this->loadingdate,
            'downloaddate' => $this->downloaddate,
            'confirmation' => $this->confirmation,
            'folionumber' => $this->folionumber,
            'evidencefile' => $this->evidencefile,
            'statusname' => $this->status ? $this->status->name : '',

        ];
    }
}
