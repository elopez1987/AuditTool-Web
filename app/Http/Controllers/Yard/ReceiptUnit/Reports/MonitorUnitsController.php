<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\StorageBoxStock;
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
        $data = StorageBoxStock::controlAccessUser()
            ->leftJoin('receiptunits', 'vw_storageboxstock.id', '=', 'receiptunits.id')
            ->Folio($request->input('folionumber'))
            ->Shipment($request->input('shipmentid'))
            ->Barcode($request->input('barcode'))
            ->BoxNumber($request->input('boxnumber'))
            ->Reason($request->input('razonstatusid'))
            ->whereNotIn('vw_storageboxstock.statusid', ['TR'])
            ->select('vw_storageboxstock.*');

        return DataTables::of($data)->toJson();
    }
}
