<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use Illuminate\Http\Request;

class MonitorReceiptPlantController extends Controller
{
    public function index()
    {
        $this->authorize('monitorreceiptplant');
        return view('Receipt.MonitorPlant.index');
    }


    public function getReceiptInTransit(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()
            ->leftJoin('shipmentcarriers', 'receiptasn.carrierid', 'shipmentcarriers.id')
            ->leftJoin('receiptunits', 'receiptasn.auxiliary06', '=', 'receiptunits.id')
            ->leftJoin('receiptstage','receiptasn.id','receiptstage.receiptid')
            ->leftJoin('storagelocations','receiptstage.locationid','storagelocations.id')
            ->where('receiptasn.statusid', '=', 'TA')
            ->Shipment($request->get('shipmentid'))
            ->select([
                'receiptasn.shipmentid',
                'receiptunits.boxnumber',
                'receiptunits.trucknumber',
                'receiptunits.operatorname',
                'receiptunits.phone',
                'receiptunits.outputdate as checkout',
                'shipmentcarriers.name as carriername',
                'storagelocations.name as location'
            ])
            ->distinct()
            ->get();
        $data = json_encode($data);
        return response()->json($data);
    }
}
