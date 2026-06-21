<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuthorizeOutPutController extends Controller
{
    public function reportauthorizedoutputs()
    {
        $this->authorize('reportauthorizedoutputs');
        $data = ReceiptUnit::controlAccessUser()
            ->whereStatusid('OU')->get();

        return view('Yards.ReceiptUnits.OutPuts.index', compact('data'));
    }

    public function getDataAuthorized(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,name', 'zone:id,name',
                'location:id,name', 'tracto:id,operatorname,phone,trucknumber,econumber',
                'warehouse:warehouseid,name', 'event:id,name'])
            ->select([
                'receiptunits.id',
                'receiptunits.warehouseid',
                'receiptunits.carrierid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.barcode',
                'receiptunits.shipmentid',
                'receiptunits.entrydate',
                'receiptunits.razonstatusid',
                'receiptunits.foliotruck',
                'receiptunits.statusid',
                'receiptunits.autorizaoutputdate',
            ])
            ->Folio($request->input('folionumber'))
            ->Shipment($request->input('shipmentid'))
            ->Barcode($request->input('barcode'))
            ->whereIn('receiptunits.statusid', ['OU']);

        return DataTables::of($data)->addColumn('stays', function ($data) {
            return Carbon::now()->diff($data->autorizaoutputdate)->format('%d d %H:%i h/m');
        })->toJson();
    }
}
