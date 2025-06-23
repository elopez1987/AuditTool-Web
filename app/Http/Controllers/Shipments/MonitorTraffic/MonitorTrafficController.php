<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\Shipments\MonitorTraffic;
use Illuminate\Http\Request;
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
            ->rawColumns(['statusunit', 'btn', 'statustransport', 'statusshipment', 'shipmentiactive','key'])
            ->toJson();
    }



    public function edit(OrderDeliverie $monitoranalistatrafico)
    {
        $orderDelivery = $monitoranalistatrafico;
        return view("Shipments.OrderMonitor.AnalystTraffic.edit", compact('orderDelivery'));
    }

    public function update(Request $request, OrderDeliverie $monitoranalistatrafico)
    {
        $orderDelivery = $monitoranalistatrafico;
        $this->updateHeaderToShipment($request, $orderDelivery);
        $this->updateSealToShipment($orderDelivery, $request);

        if ($request->has('comment')) {
            if ($request['comment'] != '') {
                StorageComment::createCommentary('orderdeliveries', $orderDelivery->id, $request);
            }
        }
        return $this->redirectBackInfo();
    }


}
