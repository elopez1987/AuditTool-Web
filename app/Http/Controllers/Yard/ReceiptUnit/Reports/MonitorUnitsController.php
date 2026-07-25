<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Traits\Web\Yard\ReceiptUnit\QueryReceiptUnits;
use Carbon\Carbon;
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
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->Folio($request->input('folionumber'))
            ->Barcode($request->input('barcode'))
            ->BoxNumber($request->input('boxnumber'))
            ->Rason($request->input('razonstatusid'))
            ->Shipment($request->input('shipmentid'))
            ->when($request->input('search_text'), function ($q, $text) {
                $q->where(function ($inner) use ($text) {
                    $inner->where('receiptunits.boxnumber', 'like', "%{$text}%")
                        ->orWhere('receiptunits.shipmentid', 'like', "%{$text}%");
                    if (is_numeric($text)) {
                        $inner->orWhere('receiptunits.id', '=', (int) $text);
                    }
                });
            })
            ->when($request->input('stay_days'), function ($q, $days) {
                $q->where('receiptunits.entrydate', '<=', Carbon::now()->subDays((int) $days));
            })
            ->when($request->input('entry_date'), function ($q, $date) {
                $q->whereDate('receiptunits.entrydate', $date);
            })
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

    public function getMonitorKpis()
    {
        $this->authorize('monitorunitsyard');

        $base = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR']);

        return response()->json([
            'total' => (clone $base)->count(),
            'critical_stay' => (clone $base)->where('receiptunits.entrydate', '<=', Carbon::now()->subDays(30))->count(),
            'no_key' => (clone $base)->whereNull('receiptunits.keydate')->count(),
            'released_today' => ReceiptUnit::controlAccessUser()
                ->whereDate('receiptunits.autorizaoutputdate', Carbon::today())
                ->count(),
        ]);
    }
}
