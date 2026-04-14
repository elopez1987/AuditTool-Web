<?php

namespace App\Http\Controllers\Yard\Yards\HenkelToluca;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use DB;

class YardHenkelTolController extends BaseController
{
    public function index()
    {
        $centers = $this->getCenterForUser();
        $warehouses = $this->getWarehouseForUser();

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
            'receiptunits.locationid', 'storagelocations.name as locationname', 'storagelocations.id as location', 'systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->get();

        $totales = DB::table('storagelocations as s')
            ->leftJoin('storagezones as z', 's.zoneid', '=', 'z.id')
            ->whereNotIn('z.zoneid', ['ST'])
            ->whereIn('s.warehouseid', $warehouses)
            ->whereIn('s.centerid', $centers)
            ->selectRaw("
						COUNT(s.locationid) AS total_ubicaciones,
						COUNT(CASE WHEN s.statusid = 'D' THEN 1 END) AS total_ocupadas,
						COUNT(s.locationid) - COUNT(CASE WHEN s.statusid = 'D' THEN 1 END) AS total_disponibles,
						ROUND(
							COUNT(CASE WHEN s.statusid = 'D' THEN 1 END) * 100.0 / COUNT(s.locationid),
							2
						) AS porcentaje_ocupacion
					")
            ->first();

        $bodegas = DB::table('storagelocations as s')
            ->leftJoin('storagezones as z', 's.zoneid', '=', 'z.id')
            ->leftJoin('systemwarehouses as w', 's.warehouseid', '=', 'w.warehouseid')
            ->whereNotIn('z.zoneid', ['ST'])
            ->whereIn('s.warehouseid', $warehouses)
            ->whereIn('s.centerid', $centers)
            ->groupBy('w.name')
            ->selectRaw("
       					 w.name AS warehouse,
					COUNT(s.locationid) AS total_ubicaciones,
					COUNT(CASE WHEN s.statusid = 'D' THEN 1 END) AS total_ocupadas,
					ROUND(
						COUNT(CASE WHEN s.statusid = 'D' THEN 1 END) * 100.0 / COUNT(s.locationid),
						2
					) AS porcentaje_ocupacion
				")
            ->get();

        return view('Yards.LayOutYard.index',
            compact('result', 'receiptUnits', 'totales', 'bodegas'));
    }
}
