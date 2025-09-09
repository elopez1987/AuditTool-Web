<?php

namespace App\Traits\Web\Yard\ReceiptUnit;

use App\Models\Yard\InventoryBox;
use App\Models\Yard\InventoryCountStock;
use App\Models\Yard\ReceiptUnit;

trait QueryInventoryBox
{

    private function insertDetailCount(InventoryBox $inventory)
    {
        $units = ReceiptUnit::whereCenterid($inventory->centerid)
            ->select(['id', 'barcode', 'boxnumber', 'zoneid', 'locationid', 'statusid'])
            ->whereNotIn('statusid', ['TR'])
            ->get();
        foreach ($units as $unit) {
            InventoryCountStock::createDetailCount($inventory, $unit);
        }
    }

    public function getInventoryBoxes($request)
    {
        return InventoryBox::controlAccessUser()
            ->leftjoin("systemcenterdistributions as s", "inventoryboxstock.centerid", "=", "s.centerid")
            ->leftjoin("systemstatus as s2", "inventoryboxstock.statusid", '=', "s2.statusid")
            ->leftjoin("systemstatus as s3", "inventoryboxstock.statustypeid", '=', "s3.statusid")
            ->leftjoin("users as u", "inventoryboxstock.createuser", '=', "u.id")
            ->ForStatus($request->get('statusid'))
            ->ForFolio($request->get('folio'))
            ->select([
                "s.name as centername", "s3.name as type", "inventoryboxstock.description",
                "inventoryboxstock.createdate", "inventoryboxstock.quantitybox",'inventoryboxstock.id as folioinv',
                "inventoryboxstock.quantityboxtrue", "u.name as user", "s2.name as status", "inventoryboxstock.id", "s2.statusid"
            ])->get();
    }

    public function getInventoryCountStocks(InventoryBox $inventorybox)
    {
        return InventoryCountStock::controlAccessUser()
            ->leftjoin("systemcenterdistributions as s", "inventorycountstock.centerid", "=", "s.centerid")
            ->leftjoin("systemstatus as s2", "inventorycountstock.statusid", '=', "s2.statusid")
            ->leftjoin("storagelocations as sl", "inventorycountstock.locationid", '=', "sl.id")
            ->leftjoin("storagelocations as sl2", "inventorycountstock.newlocationid", '=', "sl2.id")
            ->leftJoin('storagezones as sz', 'inventorycountstock.zoneid', '=', 'sz.id')
            ->leftJoin('storagezones as sz2', 'inventorycountstock.newzoneid', '=', 'sz2.id')
            ->leftJoin('users', 'inventorycountstock.userid', '=', 'users.id')
            ->select(['s.name as centername', 'inventorycountstock.countid',
                'inventorycountstock.folionumber', 'inventorycountstock.barcode', 'inventorycountstock.boxnumber',
                'sz.name as zoneid', 'sl.name as location', 'sz2.name as newzoneid', 'sl2.name as newlocationid',
                's2.name as status', 'inventorycountstock.isupdate', 'users.name as userid',
            ])->where('countid', '=', $inventorybox->id)->get();
    }

}
