<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Jobs\Embarques\Mensajes\EnviarMensajeAFacturacionJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Traits\Web\General\GetParameters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDocumentOrderDeliveryController extends BaseController
{

    use GetParameters;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = $this->updateOrderDelivery($id);
            $this->updateShipmentDataOperation($orderDelivery);
            $this->updateShipmentHeader($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (!empty($parameter) && in_array('SWH-16', (array)$parameter)) {
                EnviarMensajeAFacturacionJob::dispatch($orderDelivery);
            }

            DB::commit();
            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return OrderDeliverie
     */
    public function updateOrderDelivery($id): OrderDeliverie
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        $orderDelivery->statusid = 'DO';
        $orderDelivery->save();
        return $orderDelivery;
    }

    /**
     * @param OrderDeliverie $orderDelivery
     * @return void
     */
    public function updateShipmentDataOperation(OrderDeliverie $orderDelivery): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $shipment->senddocument = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }

    /**
     * @param OrderDeliverie $orderDelivery
     * @return void
     */
    public function updateShipmentHeader(OrderDeliverie $orderDelivery): void
    {
        $shipmentheader = ShipmentHeader::whereOrderid($orderDelivery->id)->first();
        $shipmentheader->statusid = 'DO';
        $shipmentheader->save();
    }

    /**
     * @param OrderDeliverie $orderDelivery
     * @return void
     */
    public function updateShipmentSeal(OrderDeliverie $orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)->first();
        $shipmentseal->statusorderid = 'DO';
        $shipmentseal->save();
    }


}
