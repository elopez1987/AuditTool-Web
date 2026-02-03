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

    public function getdatamonitortraffic(Request $request)
    {
        $data = $this->getshipmentsformonitortraffic($request);

        return DataTables::of($data)
            ->addColumn('btn', 'Shipments.OrderMonitor.AnalystTraffic.partials.btn')
            ->addColumn('statustransport', function ($row) {
                return $row->transport?->has_data == 1
                    ? '<span class="label label-success" style="font-size: 0.655rem;padding: 3px">Con datos</span>'
                    : '<span class="label label-danger" style="font-size: 0.655rem;color:white;padding: 3px">Sin datos</span>';
            })
            ->addColumn('key', function ($row) {
                return $row->folio?->keydate
                    ? '<span class="label label-success" style="font-size: 0.655rem;padding: 3px">Entregado</span>'
                    : '<span class="label label-danger" style="font-size: 0.655rem;color:white;padding: 3px">Pendiente</span>';
            })
            ->addColumn('precarga', fn ($row) => $row->header->location->name ?? '')
            ->addColumn('cortina', fn ($row) => $row->folio->location->name ?? '')
            ->addColumn('statusunit', function ($row) {
                if (! $row->folio?->entrydate) {
                    return '<span class="label label-warning" 
                        style="font-size: 0.675rem;color:black;padding: 3px">Sin reportarse</span>';
                }

                return $row->folio?->entrydate > $row->loadingdate
                    ? '<span class="label label-danger" style="font-size: 0.675rem;padding: 3px">Fuera de tiempo</span>'
                    : '<span class="label label-success" style="font-size: 0.675rem;padding: 3px">En tiempo</span>';
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
