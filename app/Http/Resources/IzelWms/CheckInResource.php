<?php

namespace App\Http\Resources\IzelWms;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReceiptUnit */
class CheckInResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'centerid' => $this->warehouse ? $this->warehouse->center : '',
            'warehouseid' => $this->warehouse ? $this->warehouse->warehouse : '',
            'folionumber' => $this->id,
            'barcode' => $this->barcode,
            'shipmentid' => $this->shipmentid,
            'documentid' => $this->shipmentid,
            'carrierid' => $this->carrier ? $this->carrier->carrierid : '',
            'name' => $this->carrier ? $this->carrier->name : '',
            'truckid' => $this->truck ? $this->truck->truckid : '',
            'containerseal' => $this->containerseal,
            'operatorname' => $this->operatorname,
            'unitstatus' => $this->unitstatus ? $this->unitstatus->unitstatusid : '',
            'unittype' => $this->unittype ? $this->unittype->unittypeid : '',
            'entrydate' => $this->entrydate,
            'quantitytotal' => '0',
            'quantity' => '0',
            'Droplot' => $this->location ? $this->location->locationid : '',
            'trucknumber' => $this->trucknumber,
            'boxnumber' => $this->boxnumber . '-' . $this->econumber,
            'zoneid' => $this->zone ? $this->zone->auxiliary01 : '',
            'ldastatusid' => $this->unitstatus ? $this->unitstatus->unitstatusid : '',
        ];
    }
}
