<?php

namespace App\Traits\Counting;

use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocations;
use App\Models\Inventory\FiscalInventory\Stock\InventoryStock;
use DB;

trait QueryInventoryHead
{
    public function getInventoriesFiscal($request)
    {
        return InventoryHead::controlAccessUser()
            ->leftjoin("systemcenterdistributions as s", "inventoryhead.centerid", "=", "s.centerid")
            ->leftJoin('systemwarehouses as w', 'inventoryhead.warehouseid', '=', 'w.warehouseid')
            ->leftjoin("systemstatus as s2", "inventoryhead.statusid", '=', "s2.statusid")
            ->leftjoin("systemstatus as s3", "inventoryhead.statustypeid", '=', "s3.statusid")
            ->leftjoin("users as u", "inventoryhead.createuser", '=', 'u.username')
            ->select([
                "s.name as centername", "s3.name as type", "inventoryhead.description",
                "inventoryhead.createdate", "u.name as user", "s2.name as status",
                "inventoryhead.id", "s2.statusid", "w.name as warehouse",'inventoryhead.inventorytype',
                'inventoryhead.startdate', 'inventoryhead.finishdate', 'inventorytype'
            ])
            ->ForStatus($request->get('statusid'))
            ->get();
    }

    public function getTotales(InventoryHead $inventoryhead)
    {
        $totales = InventoryStock::controlAccessUser()->select([
            DB::raw("count(DISTINCT(partnumberid)) as totalskus"),
            DB::raw("sum(quantity) as totalcajas"),
        ])->where('inventoryid', $inventoryhead->id)->first();
        return $totales;
    }

    public function getLocations(InventoryHead $inventoryhead)
    {
        $locations = InventoryLocations::controlAccessUser()
            ->where('inventoryid', $inventoryhead->id)
            ->select([DB::raw("count(locationid) as totalubicaciones"),
                DB::raw("count(DISTINCT(aisleid)) as pasillos")])->first();
        return $locations;
    }

    public function getContados(InventoryHead $inventoryhead)
    {
        return DB::select("
        select  ic.centerid,ic.warehouseid,ic.inventoryid,ic.aisleid,count(ic.cardid) as cedulas, sum(ic.locations) as totalubicaciones,
        (select count(i.statusid01) from inventorylocations i
                                    where ic.aisleid = i.aisleid and i.statusid01='TO' and i.inventoryid= $inventoryhead->id) as contados01,
        (select count(i.statusid02) from inventorylocations i
                                    where ic.aisleid = i.aisleid and i.statusid02='TO' and i.inventoryid= $inventoryhead->id) as contados02
            from inventorycards ic
            where ic.inventoryid = ?
            group by ic.aisleid, ic.inventoryid, ic.warehouseid, ic.centerid
        ", [$inventoryhead->id]);
    }

    public function getTotalCount(InventoryHead $inventoryhead)
    {
        return DB::select("
        select top 1 (select count(statusid01) from inventorylocations  where inventoryid=$inventoryhead->id and statusid01='TO' ) as totalstatus01,
             (select count(statusid02) from inventorylocations  where inventoryid=$inventoryhead->id and statusid02='TO' ) as totalstatus02
                  from inventorylocations  where inventoryid=$inventoryhead->id
        ");
    }

    public function getTotalErrorsInventory(InventoryHead $inventoryhead)
    {
        return DB::select(
            "select top 1 (select count(locationid) from vw_monitorlocationinventory vm where vm.sku='Error' and id=$inventoryhead->id) as totalSku,
                             (select count(locationid) from vw_monitorlocationinventory vm where vm.quantity='Error' and id=$inventoryhead->id) as totalQuantity
                from vw_monitorlocationinventory where id=$inventoryhead->id
            "
        );
    }

    public function getCountCard01($card)
    {
        return DB::select("select a.inventoryid,a.locationid,b.partnumberid,b.quantity,a.unitmeasureid,b.quantitylpn,b.quantityover
                    from inventorylocations a
                    left join inventorylocationscan01 b on a.cardid = b.cardid and a.inventoryid = b.inventoryid and a.locationid = b.locationid
                    where a.cardid=?
                    and a.inventoryid=? order by a.locationid asc ", [$card->cardid, $card->inventoryid]);
    }

    public function getCountCard02($card)
    {
        return DB::select("select a.inventoryid,a.locationid,b.partnumberid,b.quantity,a.unitmeasureid,b.quantitylpn,b.quantityover
                    from inventorylocations a
                    left join inventorylocationscan02 b on a.cardid = b.cardid and a.inventoryid = b.inventoryid and a.locationid = b.locationid
                    where a.cardid=?
                    and a.inventoryid=? order by a.locationid asc", [$card->cardid, $card->inventoryid]);
    }

    public function getCountCard03($card)
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
                ", [$card->cardid, $card->inventoryid, $card->cardid, $card->inventoryid]);
    }

    public function getLocationErrors($inventory)
    {
        return DB::select("
        select * from vw_monitorlocationinventory where id=$inventory and sku='Error'
            union
            select * from vw_monitorlocationinventory where id=$inventory and quantity='Error'
        ");
    }


    public function getCardInventory($request)
    {
        return InventoryCard::controlAccessUser()
            ->leftJoin('users', 'inventorycards.responsible01', '=', 'users.username')
            ->leftJoin('users as u', 'inventorycards.responsible02', '=', 'u.username')
            ->where('inventoryid', $request['inventoryid'])
            ->Aisle($request->get('aisleid'))
            ->Card($request->get('cardid'))
            ->select([
                'inventorycards.inventoryid', 'inventorycards.recordid', 'inventorycards.aisleid',
                'inventorycards.cardid', 'inventorycards.locations', 'inventorycards.startdate01',
                'inventorycards.startdate02', 'inventorycards.finishdate01', 'inventorycards.finishdate02',
                'inventorycards.unitmeasureid', 'users.name as user01', 'u.name as user02']);
    }


    public function getStockForInventory($request)
    {
        return DB::select(" select a.centerid,a.warehouseid,inventoryid,partnumberid,i.lngdsc as description,sum(a.quantity) as totalstock,
       (select top 1 (b.quantity * b.quantitylpn) + b.quantityover as total
                from inventorylocationscan01 b where b.inventoryid=? and a.partnumberid = b.partnumberid
                                                    and a.centerid = b.centerid ) as tconteo01,
       (select top 1 (c.quantity * c.quantitylpn) + c.quantityover as total
                from inventorylocationscan02 c where c.inventoryid=? and a.partnumberid = c.partnumberid
                                                    and a.centerid = c.centerid ) as tconteo02
            from dbo.inventorystock a
            join dbo.inventorypartnumbers i on a.partnumberid = i.prtnum and a.centerid = i.centerid
            where a.inventoryid=?
            group by a.centerid, a.warehouseid, a.inventoryid, partnumberid, i.lngdsc
                ", [$request['inventoryid'], $request['inventoryid'], $request['inventoryid']]);
    }

    public function getLocationNumberCount($request)
    {
        return InventoryLocations::controlAccessUser()
            ->leftJoin('systemstatus as s1', 'inventorylocations.statusid01', '=', 's1.statusid')
            ->leftJoin('systemstatus as s2', 'inventorylocations.statusid02', '=', 's2.statusid')
            ->leftJoin('systemstatus as s3', 'inventorylocations.statusid03', '=', 's3.statusid')
            ->Card($request['cardid'])
            ->Location($request['locationid'])
            ->Aisle($request['aisleid'])
            ->where('inventorylocations.inventoryid', $request['inventoryid'])
            ->select([
                'inventorylocations.*',
                's1.name as status1',
                's2.name as status2',
                's3.name as status3',
            ]);
    }

    public function getCardForPDF($id)
    {
        return InventoryCard::controlAccessUser()
            ->leftJoin('inventoryhead', 'inventoryhead.id', '=', 'inventorycards.inventoryid')
            ->where('inventorycards.recordid', '=', $id)
//            ->where('inventoryhead.statusid', '=', 'AT')
            ->first();
    }


    public function getLocationsForCountDron($inventory, $card)
    {
        return InventoryLocationForDron::select([
                'inventorylocationsdron.recordid',
                'inventorylocationsdron.areaid',
                'inventorylocationsdron.aisleid',
                'inventorylocationsdron.cardid',
                'inventorylocationsdron.locationid',
                'inventorylocationsdron.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'inventorylocationsdron.quantity',
                'inventorylocationsdron.countdate',
                'inventorylocationsdron.usercount',
                'inventorylocationsdron.usercheck',
                'inventorylocationsdron.error',
                'inventorylocationsdron.validatedate',
                'inventorylocationsdron.statusid',
                'systemstatus.name as status',
                'inventorystock.lpnid as lpns',
                'inventorystock.partnumberid as partnumberstock'
            ]
        )
            ->leftJoin('inventorystock', function ($join) {
                $join->on('inventorylocationsdron.inventoryid', '=', 'inventorystock.inventoryid');
                $join->on('inventorylocationsdron.locationid', '=', 'inventorystock.locationid');
                $join->on('inventorylocationsdron.centerid', '=', 'inventorystock.centerid');
            })
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('inventorylocationsdron.partnumberid', '=', 'inventorypartnumbers.prtnum');
                $join->on('inventorylocationsdron.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('systemstatus', function ($join) {
                $join->on('inventorylocationsdron.statusid', '=', 'systemstatus.statusid');
                $join->on('inventorylocationsdron.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('users','inventorylocationsdron.usercount','users.username')
            ->where('inventorylocationsdron.inventoryid', $inventory->id)
            ->where('inventorylocationsdron.cardid', $card->cardid)
            ->get();
    }

    public function getTotalForStatusCountDron($inventory, $card)
    {
        return InventoryLocationForDron::selectRaw('systemstatus.name, count(systemstatus.statusid) as total')
            ->leftJoin('systemstatus', function ($join) {
                $join->on('inventorylocationsdron.statusid', '=', 'systemstatus.statusid');
                $join->on('inventorylocationsdron.schemaid', '=', 'systemstatus.schemaid');
            })
            ->where('inventorylocationsdron.inventoryid', $inventory->id)
            ->where('inventorylocationsdron.cardid', $card->cardid)
            ->groupBy('systemstatus.name')
            ->get();
    }
}
