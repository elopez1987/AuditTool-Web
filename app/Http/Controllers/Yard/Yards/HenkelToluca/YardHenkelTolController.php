<?php

namespace App\Http\Controllers\Yard\Yards\HenkelToluca;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use DB;

class YardHenkelTolController extends BaseController
{
    public function index()
    {
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
            'receiptunits.entrydate', 'receiptunits.startdate', 'receiptunits.finishdate',
            'receiptunits.locationid', 'storagelocations.name as locationname', 'storagelocations.id as location', 'systemstatus.name as status',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->get();

        return view('Yards.LayOutYard.index', compact('result', 'receiptUnits'));
    }
}
