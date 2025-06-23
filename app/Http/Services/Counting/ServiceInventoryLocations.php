<?php

namespace App\Http\Services\Counting;

use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocations;

class ServiceInventoryLocations
{
    public function createNewLocation($head, $request)
    {
        return InventoryLocations::updateOrCreate([
            'centerid' => $head->centerid,
            'warehouseid' => $head->warehouseid,
            'inventoryid' => $head->id,
            'locationid' => $request['locationid'],
        ], [
            'areaid' => $request['areaid'],
            'aisleid' => $request['aisleid'],
            'cardid' => $request['cardid'],
            'type' => $request['type'],
            'unitmeasureid' => $request['unitmeasureid'],
        ]);
    }

    public function clearCountInventory($id,$request)
    {
        $card = InventoryCard::controlAccessUser()
            ->where('inventoryid', '=',$request['inventoryid'])
            ->where('recordid', '=',$id)->first();

        if ($request['typecount'] == 1) {
            $card->startdate01 = null;
            $card->responsible01 = null;
            $card->finishdate01 = null;
        }
        if ($request['typecount'] == 2) {
            $card->startdate02 = null;
            $card->responsible02 = null;
            $card->finishdate02 = null;
        }
        $card->save();
    }
}
