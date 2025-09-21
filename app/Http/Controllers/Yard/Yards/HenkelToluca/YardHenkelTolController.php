<?php

namespace App\Http\Controllers\Yard\Yards\HenkelToluca;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use DB;

class YardHenkelTolController extends BaseController
{
    public function index()
    {
        $result = ReceiptUnit::controlAccessUser()->select(['storagereasons.name as razonname', 'receiptunits.razonstatusid', DB::raw('count(receiptunits.razonstatusid) as total')])
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->groupBy('storagereasons.name', 'receiptunits.razonstatusid')
            ->get();

        $receiptUnits = ReceiptUnit::controlAccessUser()->select(['receiptunits.id as folionumber', 'receiptunits.barcode',
            'receiptunits.shipmentid', 'shipmentcarriers.name as carriername','receiptunits.statusid','receiptunits.ensnare',
            'receiptunits.boxnumber', 'receiptunits.trucknumber', 'receiptunits.operatorname','receiptunits.razonstatusid',
            'receiptunits.phone', 'storageunitstatus.unitstatusid', 'storagereasons.name as razonname',
            'receiptunits.entrydate','receiptunits.startdate','receiptunits.finishdate',
            'receiptunits.locationid', 'storagelocations.name as locationname','storagelocations.id as location','systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->get();

        $unitsDummy = ReceiptUnit::select(['receiptunits.id as folionumber', 'receiptunits.barcode',
            'receiptunits.shipmentid', 'shipmentcarriers.name as carriername','receiptunits.statusid','receiptunits.ensnare',
            'receiptunits.boxnumber', 'receiptunits.trucknumber', 'receiptunits.operatorname','receiptunits.razonstatusid',
            'receiptunits.phone', 'storageunitstatus.unitstatusid', 'storagereasons.name as razonname',
            'receiptunits.entrydate','receiptunits.startdate','receiptunits.finishdate',
            'receiptunits.locationid', 'storagelocations.name as locationname','storagelocations.id as location','systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->where('receiptunits.centerid', '=', 'HENKELTOL01')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->whereNotIn('receiptunits.statusid', ['OU'])
            ->where('storagelocations.id', '=', '30177')
            ->get();

        $receiptUnitsOU = ReceiptUnit::controlAccessUser()->select(['receiptunits.id as folionumber', 'receiptunits.barcode',
            'receiptunits.shipmentid', 'shipmentcarriers.name as carriername','receiptunits.statusid','receiptunits.ensnare',
            'receiptunits.boxnumber', 'receiptunits.trucknumber', 'receiptunits.operatorname','receiptunits.razonstatusid',
            'receiptunits.phone', 'storageunitstatus.unitstatusid', 'storagereasons.name as razonname',
            'receiptunits.entrydate','receiptunits.startdate','receiptunits.finishdate',
            'receiptunits.locationid', 'storagelocations.name as locationname','storagelocations.id as location','systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->where('receiptunits.statusid', '=', 'OU')
            ->get();

        $receiptUnitsRes = ReceiptUnit::controlAccessUser()->select(['receiptunits.id as folionumber', 'receiptunits.barcode',
            'receiptunits.shipmentid', 'shipmentcarriers.name as carriername','receiptunits.statusid','receiptunits.ensnare',
            'receiptunits.boxnumber', 'receiptunits.trucknumber', 'receiptunits.operatorname','receiptunits.razonstatusid',
            'receiptunits.phone', 'storageunitstatus.unitstatusid', 'storagereasons.name as razonname',
            'receiptunits.entrydate','receiptunits.startdate','receiptunits.finishdate',
            'receiptunits.locationid', 'storagelocations.name as locationname','storagelocations.id as location','systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->where('receiptunits.razonstatusid', '=', '3')
            ->whereNotIn('receiptunits.statusid', ['OU'])
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->get();

        return view('Yards.LayOutYard.Yards.HenkelTolucaOneRoof.index', compact('result', 'receiptUnits', 'receiptUnitsOU', 'receiptUnitsRes', 'unitsDummy'));
    }
}
