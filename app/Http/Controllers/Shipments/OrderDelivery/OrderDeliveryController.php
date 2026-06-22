<?php

namespace App\Http\Controllers\Shipments\OrderDelivery;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\OrderDelivery\CreateOrderRequest;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Shipments\Bridgestone\Verificacion\Storagechecklocationbridg;
use App\Models\Shipments\Bridgestone\Verificacion\StoragechecklocationbridgVig;
use App\Models\Shipments\Bridgestone\Verificacion\Storageverificationpending;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ViewShipmentEvent;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\Shipments\OrderDelivery\CreateNewOrder;
use App\Traits\Web\Shipments\OrderDelivery\UpdateOrderDelivery;
use App\Traits\Web\Shipments\OrderMonitor\UpdateMaximunOutPutShipment;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderDeliveryController extends BaseController
{
    use CreateNewOrder;
    use UpdateMaximunOutPutShipment;
    use UpdateOrderDelivery;

    public function index()
    {
        $this->authorize('orderdeliveries.index');
        $status = $this->getStatusForSchema('KLE');

        return view('Shipments.OrderDeliveries.index', compact('status'));
    }

    public function getDataSchedule(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01', 'carrier:id,carrierid,name',
                'turns:id,name', 'warehouse:warehouseid,name',
                'customer:id,customerid,name,city'])
            ->select([
                'orderdeliveries.id',
                'orderdeliveries.warehouseid',
                'orderdeliveries.workday',
                'orderdeliveries.turnid',
                'orderdeliveries.shipmentid',
                'orderdeliveries.carrierid',
                'orderdeliveries.customerid',
                'orderdeliveries.downloaddate',
                'orderdeliveries.confirmation',
                'orderdeliveries.statusid',
                'orderdeliveries.evidencefile',
            ])
            ->ForStatus($request->input('statusid'))
            ->Shipmentid($request->input('shipmentid'))
            ->Workday($request->input('workday'))
            ->Turn($request->input('turnid'));

        return $this->showDataToView($data, 'Shipments.OrderDeliveries.partials.buttons');
    }

    public function create()
    {
        $this->authorize('orderdeliveries.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $carriers = $this->getCarriersForSelect();
        $turns = $this->getTurns();
        $customers = $this->getOrderCustomerForSelect();
        $shipmenttypes = $this->getShipmentTypeToSelect();

        return view(
            'Shipments.OrderDeliveries.create',
            compact('carriers', 'turns', 'customers', 'shipmenttypes', 'centers', 'warehouses')
        );
    }

    public function store(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $shipment = OrderDeliverie::create($request->validated());
            $this->createNewOrder($shipment, $request, 'Model');
            OrderdeliveryTrace::saveEventShipment($shipment, 'EEM-01', 'AP');
            DB::commit();

            return $this->successResponse('orderdeliveries.index');
        } catch (Exception $e) {
            DB::rollback();
            $error = $e->getMessage();

            return $this->errorResponse($error);
        }
    }

    public function show(OrderDeliverie $orderdelivery)
    {
        $this->authorize('orderdeliveries.show');
        $evidences = EvidencePhoto::where('model_id', $orderdelivery->id)
            ->where('model_type', '=', 'OrderDelivery')
            ->where('auxiliary01', '=', 'SH')->get();
        $traces = StorageUnitTrace::controlAccessUser()->where('receiptunitid', $orderdelivery->folionumber)->get();

        $comments = StorageComment::where('modeltype', 'orderdeliveries')->where('modelid', $orderdelivery->id)->get();
        $events = ViewShipmentEvent::controlAccessUser()->where('embarque', $orderdelivery->shipmentid)->first();
        $tracesShipment = OrderdeliveryTrace::with(['user:id,username,name', 'event:statusid,name',
            'statusold:statusid,name', 'statusnew:statusid,name'])->where('orderid', $orderdelivery->id)
            ->orderBy('created_at', 'desc')->get();

        $conteos = Storagechecklocationbridg::controlAccessUser()
            ->where('orderid', $orderdelivery->id)
            ->where('model_type', 'OrderDelivery')
            ->get();
        $conteosVig = StoragechecklocationbridgVig::controlAccessUser()
            ->where('orderid', $orderdelivery->id)
            ->where('model_type', 'OrderDelivery')
            ->get();

        $countsTotals = Storageverificationpending::with(['status:statusid,name'])
            ->where('orderid', $orderdelivery->id)->get();

        return view(
            'Shipments.OrderDeliveries.show',
            compact(
                'orderdelivery',
                'evidences',
                'traces',
                'conteos',
                'comments',
                'conteosVig',
                'events',
                'tracesShipment',
                'countsTotals'
            )
        );
    }

    public function edit(OrderDeliverie $orderdelivery)
    {
        $this->authorize('orderdeliveries.edit');
        $carriers = $this->getCarriersForSelect();
        $turns = $this->getTurns();
        $customers = $this->getOrderCustomerForSelect();
        $shipmenttypes = $this->getShipmentTypeToSelect();

        return view(
            'Shipments.OrderDeliveries.edit',
            compact('carriers', 'turns', 'customers', 'shipmenttypes', 'orderdelivery')
        );
    }

    public function update(OrderDeliverie $orderdelivery, Request $request)
    {
        DB::beginTransaction();
        try {
            $orderdelivery->update($request->all());
            $this->updateMaximunOutPutShipment($orderdelivery);
            $this->editOrderDelivery($orderdelivery, $request);
            DB::commit();

            return $this->successResponse('orderdeliveries.index');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse($error);
        }
    }

    public function destroy(OrderDeliverie $orderdelivery)
    {
        $this->authorize('orderdeliveries.delete');
        try {
            $orderdelivery->delete();
        } catch (AuthorizationException $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());

            return $this->errorResponse('No tienes permisos para eliminar este registro', 403);
        } catch (ModelNotFoundException $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());

            return $this->errorResponse('El registro no fue encontrado', 404);
        } catch (QueryException $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());

            return $this->errorResponse('Error en la base de datos al intentar eliminar el registro');
        } catch (Exception $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());

            return $this->errorResponse('Ocurrió un error inesperado: ' . $e->getMessage());
        }

        return redirect()->route('orderdeliveries.index')->with(session()->flash('success', 'Registro eliminado con éxito'));
    }
}
