<?php

namespace App\Http\Controllers\Shipments\MonitorGeneral;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderMonitorSchedulle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderMonitorController extends BaseController
{

//    Monitor general
    public function index()
    {
        $this->authorize('ordermonitor.index');
        $status = $this->getStatusForSchema('KLE');
        $turns = $this->getTurns();
        return view('Shipments.OrderMonitor.MonitorGeneral.index', compact('status', 'turns'));
    }

    public function getDataMonitor(Request $request)
    {
        $data = OrderMonitorSchedulle::controlAccessUser()
            ->Shipmentid($request->get('shipmentid'))
            ->ForStatus($request->get('statusid'))
            ->get();
        return DataTables::of($data)
            ->addColumn('autorizarsalida', 'Shipments.OrderMonitor.MonitorGeneral.time.autorizarsalida')
            ->addColumn('iniciofact', 'Shipments.OrderMonitor.MonitorGeneral.time.iniciofact')
            ->addColumn('doctufac', 'Shipments.OrderMonitor.MonitorGeneral.time.doctufac')
            ->addColumn('carga', 'Shipments.OrderMonitor.MonitorGeneral.time.carga')
            ->addColumn('arribo', 'Shipments.OrderMonitor.MonitorGeneral.time.carga')
            ->addColumn('preparacion', 'Shipments.OrderMonitor.MonitorGeneral.time.preparacion')
            ->rawColumns(['autorizarsalida','iniciofact','doctufac','carga','arribo','preparacion'])
            ->toJson();
    }


}
