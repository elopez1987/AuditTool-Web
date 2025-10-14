<?php

namespace App\Traits\Web\Yard\VirtualUnit;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\GetParameters;
use Illuminate\Support\Facades\Auth;

trait NewUnitVirtual
{
    use GetParameters;

    public function newUnitVirtual($model)
    {
        $barcodebox = $this->getFolioYMS($model->centerid);
        $location = $this->getLocationDummy($model->centerid);
        $zone = SystemDetailParameter::where('parameterid', '=', 'PARAM-ZONE')
            ->where('value01', $model->centerid)
            ->where('value02', $model->warehouseid)->first();
        $truck = SystemDetailParameter::where('parameterid', '=', 'PARAM-TRUCK')
            ->where('value01', $model->centerid)->first();

        return ReceiptUnit::create([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'barcode' => $barcodebox->folionumber,
            'shipmentid' => $model->shipmentid,
            'carrierid' => $model->carrierid,
            'truckid' => $truck->value02,
            'containerseal' => '0',
            'operatorname' => 'Operador Virtual',
            'entrydate' => now(),
            'zoneid' => $zone->value03,
            'locationid' => $location->id,
            'unittypeid' => '2',
            'trucknumber' => 'AmazonTruck',
            'boxnumber' => 'AmazonBox',
            'phone' => '0',
            'razonstatusid' => '1',
            'unitstatusid' => '1',
            'drivernumber' => '0',
            'truckcategoryid' => 'CS',
            'createuser' => Auth::user()->id,
            'statusid' => 'EN',
            'auxiliary01' => '0',
            'auxiliary03' => 'VR',
            'econumber' => '0',
        ]);
    }
}
