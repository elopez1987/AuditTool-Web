<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
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
        $data = $this->getDataMonitor($request);

        return DataTables::of($data)
            ->addColumn('statusunit', 'Shipments.OrderMonitor.AnalystTraffic.partials.statusunit')
            ->addColumn('btn', 'Shipments.OrderMonitor.AnalystTraffic.partials.btn')
            ->addColumn('shipmentiactive', 'Shipments.OrderMonitor.AnalystTraffic.partials.shipment')
            ->editColumn('statustransport', 'Shipments.OrderMonitor.AnalystTraffic.partials.statustransport')
            ->addColumn('statusshipment', 'Shipments.OrderMonitor.MonitorOperation.partials.statusshipment')
            ->addColumn('key', 'Shipments.OrderMonitor.MonitorOperation.partials.statuskey')
            ->rawColumns(['statusunit', 'btn', 'statustransport', 'statusshipment', 'shipmentiactive', 'key'])
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

    public function savesealstoshipment(Request $request, $id)
    {
        $seal = ShipmentSeal::where('orderid', $id)->first();
        if (empty($seal)) {
            return redirect()->back()->with('error', 'No se encontró el registro de embarque');
        }
        $seal->containerseal01 = $request['containerseal01'];
        $seal->containerseal02 = $request['containerseal02'];
        $seal->containerseal03 = $request['containerseal03'];
        $seal->receivername = $request['receivername'];
        $seal->save();

        return $this->redirectBackInfo();
    }

    public function savecommenttoshipment(Request $request, $id)
    {
        if ($request->has('comment')) {
            if ($request['comment'] !== '') {
                StorageComment::createCommentary('orderdeliveries', $id, $request);
            }
        }

        return $this->redirectBackInfo();
    }
}
