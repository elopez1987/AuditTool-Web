<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportStayController extends BaseController
{
    public function index()
    {
        $this->authorize('reportstays');

        $carriers = $this->getCarriersForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $rason = $this->typeReasonInActiveToSelect();
        $status = $this->getStatusForSchema('RUS');
        $unitstatus = StorageUnitStatus::select(['id', 'name'])->get();

        return view('Yards.ReportStays.index',
            compact('carriers', 'warehouses', 'rason', 'status', 'unitstatus'));
    }

    public function getDataStays(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['status:statusid,name', 'carrier:id,name', 'unitstatus:id,name',
                'location:id,name', 'warehouse:warehouseid,name', 'reason:id,name', 'unittype:id,description',
                'truckcategory:truckcategoryid,description', 'truck:id,name',
            ])
            ->select([
                'receiptunits.id',
                'receiptunits.warehouseid',
                'receiptunits.shipmentid',
                'receiptunits.carrierid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.unittypeid',
                'receiptunits.unitstatusid',
                'receiptunits.locationid',
                'receiptunits.statusid',
                'receiptunits.entrydate',
                'receiptunits.autorizaoutputdate',
                'receiptunits.razonstatusid',
                'receiptunits.razonstatusid',
                'receiptunits.truckcategoryid',
                'receiptunits.truckid',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->Folio($request->input('folionumber'))
            ->Barcode($request->input('barcode'))
            ->BoxNumber($request->input('boxnumber'))
            ->Carrier($request->input('carrierid'))
            ->Warehouse($request->input('warehouseid'))
            ->Status($request->input('statusid'))
            ->Rason($request->input('rasonid'))
            ->UnitStatus($request->input('unitstatusid'));

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
