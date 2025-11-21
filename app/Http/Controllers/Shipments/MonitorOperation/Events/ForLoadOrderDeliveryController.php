<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Http\Services\Yard\AssingDoorToUnitFromShipment;
use App\Jobs\Embarques\Mensajes\EnviarMensajeOperadorPorAsignacionDeCortinaJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentCheck;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Traits\Web\Yard\VirtualUnit\CheckOutVirtual;
use App\Traits\Web\Yard\VirtualUnit\NewUnitVirtual;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ForLoadOrderDeliveryController extends BaseController
{
    use CheckOutVirtual;
    use NewUnitVirtual;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDeliveryOld = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
            $statusold = $orderDeliveryOld->statusid;

            $this->updateShipmentDataOperation($id);
            $this->updateShipmentHeader($id);
            $this->updateShipmentSeal($id);
            $orderDelivery = $this->updateOrderDelivery($id);
            $parameterWarehouse = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            $parameterCustomer = $this->getParameterOfCustomer($orderDelivery->customerid);
            $this->createUnitVirtualForCustomer($parameterCustomer, $orderDelivery);
            $this->sendMessageToOperator($parameterWarehouse, $orderDelivery);

            //          Validamos el cierre del proceso de verificación
            if (! empty($parameterWarehouse) && in_array('SWH-34', (array) $parameterWarehouse)) {
                $shipmentCheck = ShipmentCheck::where('orderid', '=', $orderDelivery->id)->first();
                if ($shipmentCheck->statusid !== 'VY') {
                    return $this->errorResponse('El embarque no se encuentra verificado.');
                }
            }

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-06', $statusold);

            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateShipmentDataOperation($id): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->forload = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }

    private function updateShipmentHeader($id): void
    {
        $shipmentheader = ShipmentHeader::controlAccessUser()->whereOrderid($id)->first();
        $shipmentheader->statusid = 'PC';
        $shipmentheader->save();
    }

    private function updateShipmentSeal($id): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($id)->first();
        $shipmentseal->statusorderid = 'PC';
        $shipmentseal->save();
    }

    private function updateOrderDelivery($id): OrderDeliverie
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        $orderDelivery->statusid = 'PC';
        $orderDelivery->save();

        return $orderDelivery;
    }

    private function createUnitVirtualForCustomer($parameterCustomer, OrderDeliverie $orderDelivery): void
    {
        //      Creamos la unidad virtual si el cliente tiene activo el parámetro OCF-04
        if (! empty($parameterCustomer) && in_array('OCF-04', (array) $parameterCustomer)) {
            $receiptunit = $this->newUnitVirtual($orderDelivery);
            $orderDelivery->folionumber = $receiptunit->id;
            $orderDelivery->save();
        }
    }

    private function sendMessageToOperator($parameterWarehouse, OrderDeliverie $orderDelivery): void
    {
        //     Proceso para enviar mensaje a operador que está en patio para enrampar
        if (! empty($parameterWarehouse) && in_array('SWH-43', (array) $parameterWarehouse)) {
            $assingDoorService = new AssingDoorToUnitFromShipment;
            EnviarMensajeOperadorPorAsignacionDeCortinaJob::dispatch($orderDelivery, $assingDoorService);
        }
    }
}
