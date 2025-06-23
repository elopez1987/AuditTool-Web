<?php

namespace App\Http\Resources\Yard;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReceiptUnit */
class ReceiptUnitsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'centerid' => $this->centerid,
            'centername' => $this->center ? $this->center->description : '',
            'warehouse' => $this->warehouse ? $this->warehouse->name : '',
            'warehouseid' => $this->warehouseid,
            'folionumber' => (string)$this->id,
            'barcode' => $this->barcode,
            'shipmentid' => $this->shipmentid,
            'carrierid' => $this->carrierid,
            'carriername' => $this->carrier ? $this->carrier->name : '',
            'truckid' => $this->truck ? $this->truck->name : '',
            'containerseal' => $this->containerseal,
            'boxnumber' => $this->boxnumber,

            'foliotruck' => $this->foliotruck,
            'operatorname' => $this->tracto ? $this->tracto->operatorname : '',
            'phone' => $this->tracto ? $this->tracto->phone : '',
            'trucknumber' => $this->tracto ? $this->tracto->trucknumber : '',
            'drivernumber' => $this->tracto ? $this->tracto->drivernumber : '',

            'entrydate' => $this->entrydate,
            'zoneid' => $this->zoneid,
            'zona' => $this->zone ? $this->zone->name : '',
            'location' => $this->location ? $this->location->name : '',
            'estatusunidad' => $this->unitstatus ? $this->unitstatus->name : '',
            'unitstatus' => $this->unitstatus ? $this->unitstatus->name : '',
            'unitstatusid' => $this->unitstatus ? $this->unitstatus->unitstatusid : '',
            'estatus' => $this->status ? $this->status->name : '',
            'razonstatusid' => $this->razonstatusid,
            'razonname' => (string)$this->reason ? $this->reason->name : '',
            'econumber' => $this->econumber,
            'statusid' => $this->statusid,
            'unittypeid' => $this->unittypeid,
            'unittypename' => $this->unittype ? $this->unittype->description : '',
            'truckcategoryid' => $this->truckcategoryid,
            'truckcategoryname' => $this->truckcategory ? $this->truckcategory->description : '',
            'typeshipment' => $this->folioclient ? $this->folioclient->auxiliary01 : '',
            'deliverdate' => $this->deliverdate,
            'ensnare' => $this->ensnare,
            'keydate' => $this->keydate,
            'startdate' => $this->startdate,
            'finishdate' => $this->finishdate,
            'unravel' => $this->unravel,
            'toreceivedate' => $this->toreceivedate,
            'autorizaoutputdate' => $this->autorizaoutputdate,
            'outputdate' => $this->outputdate,
            'auxiliary01' => $this->auxiliary01,
            'auxiliary02' => $this->auxiliary02,
            'auxiliary03' => $this->auxiliary03,
            'auxiliary04' => $this->auxiliary04,
            'auxiliary05' => $this->auxiliary05,
        ];
    }
}
