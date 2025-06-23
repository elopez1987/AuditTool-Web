<?php

namespace App\Http\Services\Counting;

use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan03;
use Auth;

class ServiceAdjustmentInventory
{

    public function adjustmentcount01($count01)
    {
        $originalCount = InventoryLocationScan03::create([
            'centerid' => $count01->centerid,
            'warehouseid' => $count01->warehouseid,
            'inventoryid' => $count01->inventoryid,
            'cardid' => $count01->cardid,
            'locationid' => $count01->locationid,
            'partnumberid01' => $count01->partnumberid,
            'quantity01' => $count01->quantity,
            'createuser' => Auth::user()->createuser,
            'type' => 'ORG'
        ]);
        return $originalCount;
    }

    /**
     * @param $count01
     * @param $count02
     * @return void
     */
    public function saveAdjustmentInventory($count01, $count02): void
    {
        InventoryLocationScan03::create([
            'centerid' => $count01->centerid,
            'warehouseid' => $count01->warehouseid,
            'inventoryid' => $count01->inventoryid,
            'cardid' => $count01->cardid,
            'locationid' => $count01->locationid,
            'partnumberid01' => $count01->partnumberid,
            'quantity01' => $count01->quantity,
            'partnumberid02' => $count02->partnumberid,
            'quantity02' => $count02->quantity,
            'createuser' => Auth::user()->createuser,
            'type' => 'ADJ'
        ]);
    }


    public function saveAdjustmentInventoryForDron($location, $partnumber, $quantity)
    {
        InventoryLocationScan03::create([
            'centerid' => $location->centerid,
            'warehouseid' => $location->warehouseid,
            'inventoryid' => $location->inventoryid,
            'cardid' => $location->cardid,
            'locationid' => $location->locationid,
            'partnumberid01' => $location->partnumberid,
            'quantity01' => $location->quantity,
            'partnumberid02' => $partnumber,
            'quantity02' => $quantity,
            'createuser' => Auth::user()->createuser,
            'type' => 'ADJ'
        ]);
    }

    /**
     * @param $count02
     * @param $originalCount
     * @return void
     */
    public function updateOriginalCount($count02, $originalCount): void
    {
        $originalCount->partnumberid02 = $count02->partnumberid;
        $originalCount->quantity02 = $count02->quantity;
        $originalCount->save();
    }

}
