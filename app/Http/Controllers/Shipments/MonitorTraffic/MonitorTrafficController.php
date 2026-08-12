<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentHeader;
use App\Traits\Web\Shipments\MonitorTraffic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MonitorTrafficController extends BaseController
{
    use MonitorTraffic;

    public function index()
    {
        $this->authorize('monitoranalistatrafico.index');
        $status = $this->getStatusForSchema('KLE');
        $turns = $this->getTurns();

        return view('Shipments.OrderMonitor.AnalystTraffic.index', compact('status', 'turns'));
    }

    /**
     * Medibles principales del monitor de tráfico: unidades con llaves
     * entregadas/pendientes y unidades por estatus de llegada, respetando
     * los mismos filtros que se muestran en la parte superior de la vista.
     */
    public function getMonitorTrafficKpis(Request $request)
    {
        $this->authorize('monitoranalistatrafico.index');

        $base = $this->getshipmentsformonitortraffic($request);

        $total = (clone $base)->count();

        $withKeys = (clone $base)->whereHas('folio', function ($q) {
            $q->whereNotNull('keydate');
        })->count();

        $onTime = (clone $base)->whereHas('folio', function ($q) {
            $q->whereNotNull('entrydate')->whereColumn('receiptunits.entrydate', '<=', 'orderdeliveries.loadingdate');
        })->count();

        $late = (clone $base)->whereHas('folio', function ($q) {
            $q->whereNotNull('entrydate')->whereColumn('receiptunits.entrydate', '>', 'orderdeliveries.loadingdate');
        })->count();

        return response()->json([
            'total' => $total,
            'with_keys' => $withKeys,
            'without_keys' => $total - $withKeys,
            'on_time' => $onTime,
            'late' => $late,
            'not_reported' => $total - $onTime - $late,
        ]);
    }

    public function getdatamonitortraffic(Request $request)
    {
        $data = $this->getshipmentsformonitortraffic($request);

        return DataTables::of($data)
            ->addColumn('btn', 'Shipments.OrderMonitor.AnalystTraffic.partials.btn')
            ->addColumn('statustransport', function ($row) {
                return $row->transport?->has_data == 1
                    ? '<span class="badge bg-success" style="padding: 3px;font-size: 10px">Con datos</span>'
                    : '-';
            })
            ->addColumn('key', function ($row) {
                return $row->folio?->keydate
                    ? '<span class="badge bg-success" style="padding: 3px;font-size: 10px">Entregado</span>'
                    : '-';
            })
            ->addColumn('precarga', fn ($row) => $row->header->location->name ?? '')
            ->addColumn('cortina', fn ($row) => $row->folio->location->name ?? '')
            ->addColumn('operador', fn ($row) => $row->folio->operatorname ?? '')
            ->addColumn('statusunit', function ($row) {
                if (! $row->folio?->entrydate) {
                    //                    return '<span class="badge bg-warning" style="font-size: 10px;color:white;padding: 3px">Sin reportarse</span>';
                    return '-';
                }

                return $row->folio?->entrydate > $row->loadingdate
                    ? '<span class="badge bg-danger" style="font-size: 10px !important;padding: 3px">Fuera de tiempo</span>'
                    : '<span class="badge bg-success" style="font-size: 10px !important;padding: 3px">En tiempo</span>';
            })
            ->rawColumns(['statusunit', 'btn', 'statustransport', 'key'])
            ->toJson();
    }

    public function edit(OrderDeliverie $monitoranalistatrafico)
    {
        $orderDelivery = $monitoranalistatrafico;
        $comments = StorageComment::where('modeltype', 'orderdeliveries')->where('modelid', $orderDelivery->id)
            ->orderBy('id', 'desc')->get();

        return view('Shipments.OrderMonitor.AnalystTraffic.edit', compact('orderDelivery', 'comments'));
    }

    public function update(Request $request, $id)
    {
        $shipmentheader = ShipmentHeader::controlAccessUser()->where('orderid', $id)->first();
        if (empty($shipmentheader)) {
            return redirect()->back()->with('error', 'No se encontró el registro de embarque');
        }
        $shipmentheader->totalpiecesfac = $request['totalpiecesfac'];
        $shipmentheader->totalcontainersfac = $request['totalcontainersfac'];
        $shipmentheader->createuserid = Auth::user()->id;
        $shipmentheader->save();

        return $this->redirectBackInfo();
    }
}
