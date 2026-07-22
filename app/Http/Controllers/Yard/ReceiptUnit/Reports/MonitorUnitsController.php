<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Traits\Web\Yard\ReceiptUnit\QueryReceiptUnits;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MonitorUnitsController extends Controller
{
    use QueryReceiptUnits;

    public function index()
    {
        $this->authorize('monitorunitsyard');
        $razon = StorageReason::where('type', '=', 'IN')->select('name', 'id')->get();

        return view('Yards.MonitorUnits.index', compact('razon'));
    }

    public function getDataStockBox(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,name', 'event:id,name'])
            ->whereNotIn('statusid', ['TR'])
            ->Folio($request->input('folionumber'))
            ->Barcode($request->input('barcode'))
            ->BoxNumber($request->input('boxnumber'))
            ->Rason($request->input('razonstatusid'))
            ->Shipment($request->input('shipmentid'))
            ->select([
                'receiptunits.id',
                'receiptunits.barcode',
                'receiptunits.shipmentid',
                'receiptunits.carrierid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.entrydate',
                'receiptunits.deliverdate',
                'receiptunits.keydate',
                'receiptunits.ensnare',
                'receiptunits.startdate',
                'receiptunits.finishdate',
                'receiptunits.autorizaoutputdate',
                'receiptunits.razonstatusid',
            ]);

        return DataTables::of($data)->toJson();
    }
}
