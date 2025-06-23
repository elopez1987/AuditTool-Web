<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\StorageReason;
use App\Traits\Yard\QueryReceiptUnits;
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
        $data = $this->getDataMonitorUnit($request);
        return DataTables::of($data)
            ->addColumn('shipmentbutton', "Yards.ReceiptUnits.partials.shipment")
            ->rawColumns(['shipmentbutton'])
            ->toJson();
    }




}

