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
            'shipmentid' => $this->shipmentid ?? '000000',
            'documentid' => $this->shipmentid ?? '000000',
            'carrierid' => $this->carrier ? $this->carrier->carrierid : '',
            'name' => $this->carrier ? $this->carrier->name : 'LINEA DE TRANSPORTE',
            'truckid' => $this->truck ? $this->truck->truckid : 'CAJA',
            'containerseal' => $this->containerseal ?? '00',
            'operatorname' => $this->tracto ? $this->tracto->operatorname : 'SIN OPERADOR',
            'unitstatus' => $this->unitstatus ? $this->unitstatus->unitstatusid : '',
            'unittype' => $this->unittype ? $this->unittype->unittypeid : '',
            'entrydate' => $this->entrydate,
            'quantitytotal' => '0',
            'quantity' => '0',
            'droplot' => $this->location ? $this->location->locationid : '_locationid',
            'trucknumber' => $this->tracto ? $this->tracto->trucknumber : '00000',
            'boxnumber' => $this->boxnumber . '-' . $this->econumber ?? '00000',
            'zoneid' => $this->zone ? $this->zone->auxiliary01 : 'ZONA',
            'ldastatusid' => $this->unitstatus ? $this->unitstatus->unitstatusid : 'ESTATUS',
            'updstatusid' => '',
        ];
    }
}
