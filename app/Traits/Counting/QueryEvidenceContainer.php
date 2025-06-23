<?php

namespace App\Traits\Counting;

use App\Models\Evidence\EvidenceContainer;
use App\Models\Evidence\StorageEvidenceProcess;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


trait QueryEvidenceContainer
{

    public function getShipment($shipmentid)
    {
        return StorageEvidenceProcess::controlAccessUser()
            ->where('shipmentid', '=', $shipmentid)->first();
    }


    public function getSku($partnumberid)
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('prtnum', '=', $partnumberid)
            ->select(['id', 'prtnum', 'lngdsc', 'stkuom',
                'untpal as standard', 'auxiliary01 as locationid'])->first();
    }


    public function saveFile($request, $shipment)
    {
        $image = $request['file'];
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $shipment->shipmentid . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $shipment->centerid . '/EvidenceProcess/' . $imageName), base64_decode($image));
        $path = 'public/' . $shipment->centerid . '/EvidenceProcess/' . $imageName;
        return $path;
    }

    public function saveContainerToShipment($shipment, $request, $partnumberid)
    {
        $container = EvidenceContainer::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'shipmentid' => $shipment->recordid,
            'lpnid' => $request['lpnid'],
            'partnumberid' => $partnumberid->id,
            'quantity' => $request['quantity'],
            'adddatecheck' => Carbon::now(),
            'scanusercheck' => \Auth::user()->username,
            'value01' => $request['value01'],
            'value02' => $request['weight'],
        ]);
        return $container;
    }

    public function getShipmentContainer($shipment)
    {
        return DB::select(
            "select  a.recordid,a.shipmentid,b.name as customer,count(DISTINCT (c.lpnid)) as pallets
            from dbo.storageevidenceoperations a
                     left join ordercustomers b on a.customerid = b.id and a.centerid = b.centerid
                     left join storageevidencecontainers c on a.recordid = c.shipmentid and a.centerid = c.centerid
                    where   a.recordid = ?
                    group by a.recordid, a.shipmentid, b.name

        ", [$shipment->recordid]);
    }

}
