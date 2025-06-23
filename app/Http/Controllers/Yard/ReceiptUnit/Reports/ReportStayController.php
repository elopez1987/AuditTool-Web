<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportStayController extends Controller
{

    public function index()
    {
        $this->authorize('reportstays');
        return view('Yards.ReportStays.index');
    }

    public function getDataStays(Request $request)
    {
        $data = $this->getData($request);
        return DataTables::of($data)
            ->addColumn('stays', function ($data) {
                return Carbon::now()->diff($data->entrydate)->format('%m m %d d %H:%i h/m');
            })
            ->addColumn('time', function ($data) {
                return Carbon::now()->diffInHours($data->entrydate);
            })
            ->addColumn('cellstays', 'Yards.ReportStays.partials.stays')
            ->rawColumns(['cellstays'])
            ->toJson();
    }

    private function getData(Request $request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('systemstatus','receiptunits.statusid','=','systemstatus.statusid')
            ->leftJoin('shipmentcarriers','receiptunits.carrierid','=','shipmentcarriers.id')
            ->leftJoin('storagereasons','receiptunits.razonstatusid','=','storagereasons.id')
            ->leftJoin('storagelocations','receiptunits.locationid','=','storagelocations.id')
            ->leftJoin('systemwarehouses','receiptunits.warehouseid','=','systemwarehouses.warehouseid')
            ->leftJoin('storageunitstatus','receiptunits.unitstatusid','=','storageunitstatus.id')
            ->select([
                'systemwarehouses.name as warehouse', 'receiptunits.id', 'receiptunits.shipmentid',
                'shipmentcarriers.name as carrier', 'receiptunits.boxnumber', 'receiptunits.econumber',
                'storagelocations.name as location', 'storageunitstatus.name as unitstatus',
                'systemstatus.name as status', 'receiptunits.entrydate', 'storagereasons.name as razon',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->Folio($request->get('folionumber'))->get();

    }


}
