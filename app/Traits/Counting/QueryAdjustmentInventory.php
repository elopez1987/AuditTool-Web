<?php

namespace App\Traits\Counting;

use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan01;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan02;
use App\Models\Inventory\FiscalInventory\Locations\MonitorLocationInventory;
use DB;


trait QueryAdjustmentInventory
{

    public function getAllLocationsErrors($request)
    {
        return MonitorLocationInventory::join('inventorycards', function ($join) {
            $join->on('inventorycards.cardid', '=', 'vw_monitorlocationinventory.cardid')
                ->on('inventorycards.inventoryid', '=', 'vw_monitorlocationinventory.id');
        })
            ->select(['inventorycards.recordid', 'vw_monitorlocationinventory.id as inventoryid', 'vw_monitorlocationinventory.areaid', 'vw_monitorlocationinventory.aisleid',
                'vw_monitorlocationinventory.cardid', DB::raw("count(vw_monitorlocationinventory.cardid) as totalerrores")])
            ->where('vw_monitorlocationinventory.sku', '=', 'Error')
            ->orWhere('vw_monitorlocationinventory.quantity', '=', 'Error')
            ->where('vw_monitorlocationinventory.id', '=', $request['inventoryid'])
            ->Card($request->get('cardid'))
            ->Aisle($request->get('aisleid'))
            ->groupBy('vw_monitorlocationinventory.cardid', 'vw_monitorlocationinventory.aisleid', 'vw_monitorlocationinventory.id',
                'vw_monitorlocationinventory.areaid', 'inventorycards.recordid')->get();
    }


    public function getLocationWithErrorForCountDron($request)
    {
        return InventoryLocationForDron::selectRaw('inventorycards.recordid, inventorylocationsdron.inventoryid, inventorylocationsdron.areaid,
         inventorylocationsdron.aisleid, inventorylocationsdron.cardid, count(inventorylocationsdron.locationid) as totalerrores')
            ->leftJoin('inventorycards', function ($join) {
                $join->on('inventorylocationsdron.inventoryid', '=', 'inventorycards.inventoryid');
                $join->on('inventorylocationsdron.cardid', '=', 'inventorycards.cardid');
            })
            ->where('inventorylocationsdron.inventoryid', '=', $request['inventoryid'])
            ->where('inventorylocationsdron.error', '=', 'S')
            ->groupBy('inventorylocationsdron.inventoryid', 'inventorylocationsdron.areaid',
                'inventorylocationsdron.aisleid', 'inventorylocationsdron.cardid', 'inventorycards.recordid')
            ->get();
    }


    public function getLocationsForAdjustCard($card, $inventory)
    {
        return DB::select("select * from vw_monitorlocationinventory
            where cardid=?
            and id=?
            and sku='Error'
            union
            select * from vw_monitorlocationinventory
            where cardid=?
            and id=?
            and quantity='Error'
                ", [$card->cardid, $inventory->id, $card->cardid, $inventory->id]);
    }

    public function getLocationCount01($request, $i)
    {
        return InventoryLocationScan01::where('locationid', trim($request['locationid'][$i]))
            ->where('inventoryid', $request['inventoryid'])->first();
    }

    public function getLocationCount02($request, $i)
    {
        return InventoryLocationScan02::where('locationid', trim($request['locationid'][$i]))
            ->where('inventoryid', $request['inventoryid'])->first();
    }


}
