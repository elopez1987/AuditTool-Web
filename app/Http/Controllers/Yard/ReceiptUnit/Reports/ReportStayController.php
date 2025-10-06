<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ReportStayController extends Controller
{
    public function index()
    {
        $this->authorize('reportstays');

        return view('Yards.ReportStays.index');
    }

    public function getDataStays()
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['status:statusid,name', 'carrier:id,name', 'unitstatus:id,name',
                'location:id,name', 'warehouse:warehouseid,name', 'reason:id,name', 'unittype:id,description',
                'truckcategory:truckcategoryid,description', 'truck:id,name',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR'])->get();

        return DataTables::of($data)
            ->addColumn('stays', function ($data) {
                return Carbon::now()->diff($data->entrydate)->format('%m m %d d %H:%i h/m');
            })
            ->addColumn('autorize', function ($data) {
                return Carbon::now()->diff($data->autorizaoutputdate)->format('%m m %d d %H:%i h/m');
            })
            ->rawColumns(['stays', 'autorize'])->toJson();
    }
}
