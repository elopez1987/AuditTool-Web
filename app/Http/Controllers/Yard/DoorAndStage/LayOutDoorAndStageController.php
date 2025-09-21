<?php

namespace App\Http\Controllers\Yard\DoorAndStage;

use App\Http\Controllers\Controller;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;

class LayOutDoorAndStageController extends Controller
{
    public function index()
    {
        $locations = StorageLocation::controlAccessUser()
            ->leftJoin('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereIn('storagezones.zoneid', ['CO', 'ST'])
            ->select(['storagelocations.id', 'storagelocations.name', 'storagelocations.statusid', 'storagezones.zoneid'])
            ->get();
        $units = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])
            ->select(['carrierid', 'boxnumber', 'ensnare', 'razonstatusid', 'locationid', 'id', 'shipmentid', 'operatorname', 'statusid'])->get();
        $shipments = OrderDeliverie::controlAccessUser()
            ->leftJoin('shipmentheader', 'orderdeliveries.id', 'shipmentheader.orderid')
            ->select(['orderdeliveries.shipmentid', 'shipmentheader.locationid', 'orderdeliveries.id'])
            ->whereNotIn('orderdeliveries.statusid', ['EJ', 'CA', 'IF', 'DT', 'DH', 'DO'])
            ->get();
        return view(
            'Yards.LayOutDoorAndStages.index',
            compact('locations', 'units', 'shipments')
        );
    }
}
