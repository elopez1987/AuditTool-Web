<?php

namespace App\Http\Controllers\Shipments\MonitorGeneral;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderMonitorSchedulle;
use App\Traits\Web\Shipments\OrderMonitor\CreateNewOrderMonitorShipment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Yajra\DataTables\Facades\DataTables;

class OrderMonitorController extends BaseController
{
    use CreateNewOrderMonitorShipment;

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
            ->ForStatus($request->get('statusid'))->get();

        return DataTables::of($data)
            ->addColumn('autorizarsalida', 'Shipments.OrderMonitor.MonitorGeneral.time.autorizarsalida')
            ->addColumn('iniciofact', 'Shipments.OrderMonitor.MonitorGeneral.time.iniciofact')
            ->addColumn('doctufac', 'Shipments.OrderMonitor.MonitorGeneral.time.doctufac')
            ->addColumn('carga', 'Shipments.OrderMonitor.MonitorGeneral.time.carga')
            ->addColumn('arribo', 'Shipments.OrderMonitor.MonitorGeneral.time.arribo')
            ->addColumn('preparacion', 'Shipments.OrderMonitor.MonitorGeneral.time.preparacion')
            ->rawColumns(['autorizarsalida', 'iniciofact', 'doctufac', 'carga', 'arribo', 'preparacion'])
            ->toJson();
    }

    public function create()
    {
        return view('Shipments.OrderMonitor.MonitorGeneral.create');
    }

    public function store(Request $request)
    {
        $this->authorize('ordermonitor.create');
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('El número de embarque no existe, favor de validar');
        }
        DB::beginTransaction();
        try {
            $this->createNewRegisterMonitor($orderDelivery);
            DB::commit();

            return $this->redirectBackSuccessMessage('Registro agregado en el monitor correctamente.');

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudieron guardar los datos, favor de validar.');
        }
    }
}
