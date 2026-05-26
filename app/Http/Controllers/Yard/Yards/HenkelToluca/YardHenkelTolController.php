<?php

namespace App\Http\Controllers\Yard\Yards\HenkelToluca;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageZone\StorageZone;
use DB;

class YardHenkelTolController extends BaseController
{
    public function index()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        $result = ReceiptUnit::controlAccessUser()->select(['storagereasons.name as razonname',
            'receiptunits.razonstatusid', DB::raw('count(receiptunits.razonstatusid) as total')])
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->groupBy('storagereasons.name', 'receiptunits.razonstatusid')
            ->get();

        $receiptUnits = ReceiptUnit::controlAccessUser()->select(['receiptunits.id as folionumber', 'receiptunits.barcode',
            'receiptunits.shipmentid', 'shipmentcarriers.name as carriername', 'receiptunits.statusid', 'receiptunits.ensnare',
            'receiptunits.boxnumber', 'receiptunits.trucknumber', 'receiptunits.operatorname', 'receiptunits.razonstatusid',
            'receiptunits.phone', 'storageunitstatus.unitstatusid', 'storagereasons.name as razonname', 'receiptunits.statusid',
            'receiptunits.entrydate', 'receiptunits.startdate', 'receiptunits.finishdate', 'receiptunits.econumber',
            'receiptunits.locationid', 'storagelocations.name as locationname',
            'storagelocations.id as location', 'systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->get();

        $total_ubicaciones = StorageZone::controlAccessUser()
            ->whereNotIn('zoneid', ['ST'])
            ->where('statusid', 'E')
            ->whereNull('deleted_at')->sum('capacity');

        $total_ocupadas = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->count();

        $bodegas = DB::table('systemwarehouses as s')
            ->whereIn('s.warehouseid', $warehouses)
            ->selectRaw("
                s.name AS warehouse,
                (
                    SELECT SUM(s2.capacity)
                    FROM storagezones s2
                    WHERE s2.warehouseid = s.warehouseid
                      AND s2.statusid = 'E'
                      AND s2.deleted_at IS NULL
                ) AS total_ubicaciones,
                (
                    SELECT COUNT(r2.id)
                    FROM receiptunits r2
                    WHERE r2.warehouseid = s.warehouseid
                      AND r2.statusid NOT IN ('TR')
                ) AS total_ocupadas,
                ROUND(
                    (
                        SELECT COUNT(r2.id)
                        FROM receiptunits r2
                        WHERE r2.warehouseid = s.warehouseid
                          AND r2.statusid NOT IN ('TR')
                    ) * 100.0
                    / NULLIF(
                        (
                            SELECT SUM(s2.capacity)
                            FROM storagezones s2
                            WHERE s2.warehouseid = s.warehouseid
                              AND s2.statusid = 'E'
                              AND s2.deleted_at IS NULL
                        ),
                        0
                    ),
                    2
                ) AS porcentaje_ocupacion
            ")
            ->get();

        return view('Yards.LayOutYard.index',
            compact('result', 'receiptUnits', 'bodegas', 'total_ubicaciones', 'total_ocupadas'));
    }
}
