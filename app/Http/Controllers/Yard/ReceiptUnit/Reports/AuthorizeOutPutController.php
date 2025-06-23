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
            ->with(['carrier', 'zone', 'location', 'warehouse','event'])
            ->Folio($request->get('folionumber'))
            ->Shipment($request->get('shipmentid'))
            ->Barcode($request->get('barcode'))
            ->whereIn('statusid', ['OU'])->get();
        return DataTables::of($data)->addColumn('stays', function ($data) {
                return Carbon::now()->diff($data->autorizaoutputdate)->format('%d d %H:%i h/m');
            })->toJson();
    }

}
