<?php

namespace App\Http\Controllers\Shipments\OrderDelivery;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\OrderDelivery\CreateOrderRequest;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Shipments\Bridgestone\Verificacion\Storagechecklocationbridg;
use App\Models\Shipments\Bridgestone\Verificacion\StoragechecklocationbridgVig;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\Shipments\OrderDelivery\CreateNewOrder;
use App\Traits\Web\Shipments\OrderDelivery\UpdateOrderDelivery;
use App\Traits\Web\Shipments\OrderMonitor\UpdateMaximunOutPutShipment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDeliveryController extends BaseController
{
    use CreateNewOrder;
    use UpdateMaximunOutPutShipment;
    use UpdateOrderDelivery;

    public function index()
    {
        $this->authorize('orderdeliveries.index');
        $status = $this->getStatusForSchema('KLE');
        $turns = $this->getTurns();

        return view('Shipments.OrderDeliveries.index', compact('status', 'turns'));
    }

    public function getDataSchedule(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->ForStatus($request->get('statusid'))
            ->Shipmentid($request->get('shipmentid'))
            ->Workday($request->get('workday'))
            ->Turn($request->get('turnid'))
            ->select(['s.description as centername', 's2.name as warehousename', 'orderdeliveries.id', 'orderdeliveries.workday', 's3.name as turn', 'orderdeliveries.auxiliary05 as token',
                DB::raw("concat(orderdeliveries.shipmentid, '|', orderdeliveries.auxiliary03) as shipmentid"), 's4.name as carriername',
                'o2.name as customername', 'o2.city', 'orderdeliveries.downloaddate', 'orderdeliveries.confirmation', 'orderdeliveries.statusid', 'orderdeliveries.evidencefile',
                's5.name as status',
            ])
            ->leftJoin('systemcenterdistributions as s', 'orderdeliveries.centerid', '=', 's.centerid')
            ->leftJoin('systemwarehouses as s2', 'orderdeliveries.warehouseid', '=', 's2.warehouseid')
            ->leftJoin('storageturns as s3', 'orderdeliveries.turnid', '=', 's3.id')
            ->leftJoin('shipmentcarriers as s4', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 's4.id')
                    ->on('orderdeliveries.centerid', '=', 's4.centerid');
            })
            ->leftJoin('ordercustomers as o2', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'o2.id')
                    ->on('orderdeliveries.centerid', '=', 'o2.centerid');
            })
            ->leftJoin('systemstatus as s5', 'orderdeliveries.statusid', '=', 's5.statusid')
            ->orderBy('orderdeliveries.id', 'desc')
            ->limit(300)->get();

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
        $traces = StorageUnitTrace::controlAccessUser()
            ->where('receiptunitid', $orderdelivery->folionumber)->get();
        $conteos = Storagechecklocationbridg::controlAccessUser()->where('orderid', $orderdelivery->id)->get();
        $conteosVig = StoragechecklocationbridgVig::controlAccessUser()->where('orderid', $orderdelivery->id)->get();
        $comments = StorageComment::where('modeltype', 'orderdeliveries')->where('modelid', $orderdelivery->id)->get();

        return view('Shipments.OrderDeliveries.show', compact('orderdelivery', 'evidences', 'traces', 'conteos', 'comments', 'conteosVig'));
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
            return $this->errorResponse('No tienes permisos para eliminar este registro', 403);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('El registro no fue encontrado', 404);
        } catch (QueryException $e) {
            return $this->errorResponse('Error en la base de datos al intentar eliminar el registro');
        } catch (Exception $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());

            return $this->errorResponse('Ocurrió un error inesperado: ' . $e->getMessage());
        }

        return redirect()->route('orderdeliveries.index')->with(session()->flash('success', 'Registro eliminado con exito'));
    }
}
