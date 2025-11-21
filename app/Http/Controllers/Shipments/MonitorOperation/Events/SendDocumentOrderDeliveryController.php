<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Jobs\Embarques\Mensajes\EnviarMensajeAFacturacionDocumentosEnviadosJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Traits\Web\General\GetParameters;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDocumentOrderDeliveryController extends BaseController
{
    use GetParameters;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDeliveryOld = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
            $statusold = $orderDeliveryOld->statusid;
            $orderDelivery = $this->updateOrderDelivery($id);
            $this->updateShipmentDataOperation($orderDelivery);
            $this->updateShipmentHeader($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (! empty($parameter) && in_array('SWH-16', (array) $parameter)) {
                EnviarMensajeAFacturacionDocumentosEnviadosJob::dispatch($orderDelivery);
            }

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-09', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateOrderDelivery($id): OrderDeliverie
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (! empty($orderDelivery)) {
            $orderDelivery->statusid = 'DO';
            $orderDelivery->save();

            return $orderDelivery;
        }
    }

    private function updateShipmentDataOperation(OrderDeliverie $orderDelivery): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        if (! empty($shipment)) {
            $shipment->senddocument = now();
            $shipment->lastuserid = Auth::user()->id;
            $shipment->save();
        }
    }

    private function updateShipmentHeader(OrderDeliverie $orderDelivery): void
    {
        $shipmentheader = ShipmentHeader::whereOrderid($orderDelivery->id)->first();
        if (! empty($shipmentheader)) {
            $shipmentheader->statusid = 'DO';
            $shipmentheader->save();
        }
    }

    private function updateShipmentSeal(OrderDeliverie $orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)->first();
        if (! empty($shipmentseal)) {
            $shipmentseal->statusorderid = 'DO';
            $shipmentseal->save();
        }
    }
}
