<?php

namespace App\Traits\Web\Counting;

use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocations;
use App\Models\Inventory\PartNumber\InventoryPartnumber;

trait QueryInventoryScan
{

    public function getPartnumber($partnumberid)
    {
        return InventoryPartnumber::controlAccessUser()->where('prtnum', $partnumberid)
            ->select(['prtnum', 'lngdsc', 'stkuom', 'untpal as standard', 'auxiliary01 as locationid'])->first();
    }

    public function getCard($request)
    {
        return InventoryCard::controlAccessUser()
            ->leftJoin('inventoryhead', 'inventorycards.inventoryid', '=', 'inventoryhead.id')
            ->where('inventoryhead.id', $request['inventoryid'])
            ->where('inventorycards.cardid', '=', trim(strtoupper($request['cardid'])))
            ->where('inventoryhead.statusid', '=', 'AT')
//            ->where('inventorytype', 'APP')
            ->first();
    }


    public function getLocation($request)
    {
        $location = InventoryLocations::controlAccessUser()
            ->leftJoin('inventoryhead', 'inventorylocations.inventoryid', '=', 'inventoryhead.id')
            ->where('inventoryhead.statusid', '=', 'AT')
            ->where('inventorylocations.inventoryid', '=', $request['inventoryid'])
            ->where('locationid', trim(strtoupper($request['locationid'])))
            ->first();
        return $location;
    }

    public function getCountPendingForCard($location, $column)
    {
        return InventoryLocations::controlAccessUser()
            ->leftJoin('inventoryhead', 'inventorylocations.inventoryid', '=', 'inventoryhead.id')
            ->where('cardid', $location->cardid)
            ->where('inventoryhead.id', $location->inventoryid)
            ->where('inventoryhead.statusid', '=', 'AT')
            ->where($column, '=', 'PN')->get()->count();
    }

    public function getLocationPendingCard($card, $column)
    {
        return InventoryLocations::controlAccessUser()
            ->leftJoin('inventoryhead', 'inventorylocations.inventoryid', '=', 'inventoryhead.id')
            ->where('inventoryhead.statusid', '=', 'AT')
            ->where('cardid', '=', $card->cardid)
            ->where('inventoryhead.id', $card->inventoryid)
            ->where($column, '=', 'PN')
            ->select('locationid')->get();
    }

}
