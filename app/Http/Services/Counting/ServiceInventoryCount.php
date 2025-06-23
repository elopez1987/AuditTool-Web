<?php

namespace App\Http\Services\Counting;

use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan01;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan02;
use Auth;
use Illuminate\Http\Request;

class ServiceInventoryCount
{

    public function createCount01($location, Request $request)
    {
        InventoryLocationScan01::updateOrCreate([
            'centerid' => $location->centerid,
            'warehouseid' => $location->warehouseid,
            'inventoryid' => $location->inventoryid,
            'cardid' => $request['cardid'],
            'locationid' => $request['locationid'],
        ], [
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'quantitylpn' => $request['quantitylpn'],
            'quantityover' => $request['quantityover'],
        ]);

    }

    public function createCount02($location, Request $request)
    {
        InventoryLocationScan02::updateOrCreate([
            'centerid' => $location->centerid,
            'warehouseid' => $location->warehouseid,
            'inventoryid' => $location->inventoryid,
            'cardid' => $request['cardid'],
            'locationid' => $request['locationid'],
        ], [
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'quantitylpn' => $request['quantitylpn'],
            'quantityover' => $request['quantityover'],
        ]);
    }

    public function startCount01($card)
    {
        $card->startdate01 = now();
        $card->responsible01 = Auth::user()->username;
        $card->save();
    }

    public function startCount02($card)
    {
        $card->startdate02 = now();
        $card->responsible02 = Auth::user()->username;
        $card->save();
    }

    public function updateLocation($location, $status)
    {
        if ($status == 'statusid01') {
            $location->statusid01 = 'TO';
        }
        if ($status == 'statusid02') {
            $location->statusid02 = 'TO';
        }
        $location->save();
    }

}
