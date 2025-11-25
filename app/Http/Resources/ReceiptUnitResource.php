<?php

namespace App\Http\Resources;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ReceiptUnit */
class ReceiptUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'id' => $this->id,
            'folionumber' => $this->id,
            'barcode' => $this->barcode,
            'shipmentid' => $this->shipmentid,
            'boxnumber' => $this->boxnumber,
            'econumberbox' => $this->econumber,
            'containerseal' => $this->containerseal,
            'statusname' => $this->status->name,
            'zoneorg' => $this->zone->name,
            'locationorg' => $this->location->name,
            'carriername' => $this->carrier->name,
            'operatorname' => $this->tracto ? $this->tracto->operatorname : '',
            'barcodetruck' => $this->tracto ? $this->tracto->barcode : '',
            'trucknumber' => $this->tracto ? $this->tracto->trucknumber : '',
            'phone' => $this->tracto ? $this->tracto->phone : '',
            'foliotruck' => $this->tracto ? $this->tracto->id : '',
            'razonname' => $this->reason->name,
            'unitstatusname' => $this->unitstatus ? $this->unitstatus->name : '',

            //			'carrierid' => $this->carrierid,
            //			'truckid' => $this->truckid,
            //			'zoneid' => $this->zoneid,
            //			'locationid' => $this->locationid,
            //			'razonstatusid' => $this->razonstatusid,
            //			'unittypeid' => $this->unittypeid,
            //			'truckcategoryid' => $this->truckcategoryid,
            //			'foliotruck' => $this->foliotruck,
            //			'statusid' => $this->statusid,
            //			'operatorname' => $this->operatorname,
            //			'drivernumber' => $this->drivernumber,
            //			'trucknumber' => $this->trucknumber,
            //			'quantity' => $this->quantity,
            //			'type' => $this->type,
            //			'phone' => $this->phone,
            //			'entrydate' => $this->entrydate,
            //			'deliverdate' => $this->deliverdate,
            //			'ensnare' => $this->ensnare,
            //			'keydate' => $this->keydate,
            //			'startdate' => $this->startdate,
            //			'finishdate' => $this->finishdate,
            //			'unravel' => $this->unravel,
            //			'toreceivedate' => $this->toreceivedate,
            //			'autorizaoutputdate' => $this->autorizaoutputdate,
            //			'outputdate' => $this->outputdate,
            //			'auxiliary01' => $this->auxiliary01,
            //			'auxiliary02' => $this->auxiliary02,
            //			'auxiliary03' => $this->auxiliary03,
            //			'auxiliary04' => $this->auxiliary04,
            //			'auxiliary05' => $this->auxiliary05,
            //			'schemaid' => $this->schemaid,
            //			'activities_count' => $this->activities_count,
            //			'staytime' => $this->staytime,

        ];
    }
}
