<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use Illuminate\Support\Facades\DB;
use Log;

class StartGoogIssueController extends BaseController
{
    public function index(OrderDeliverie $id)
    {
        $order = $id;
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $order->id)->first();
            $this->updateShipmentHeader($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);
            $this->updateOrderDelivery($orderDelivery);
            $this->SendEdiToRControl($orderDelivery);
            DB::commit();
            return $this->redirectBackSuccessMessage('Datos agregados correctamente');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->errorResponse([$e->getMessage()]);
        }
    }

    /**
     * @param OrderDeliverie $orderDelivery
     *
     * @return void
     */
    public function updateShipmentHeader(OrderDeliverie $orderDelivery): void
    {
        $shipmentheader = ShipmentHeader::whereOrderid($orderDelivery->id)->first();
        $shipmentheader->statusid = 'IF';
        $shipmentheader->startgoodissuedate = now();
        $shipmentheader->save();
    }

    /**
     * @param OrderDeliverie $orderDelivery
     *
     * @return void
     */
    public function updateShipmentSeal(OrderDeliverie $orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($orderDelivery->id)->first();
        $shipmentseal->statusorderid = 'IF';
        $shipmentseal->save();
    }

    /**
     * @param OrderDeliverie $orderDelivery
     *
     * @return void
     */
    public function updateOrderDelivery(OrderDeliverie $orderDelivery): void
    {
        $orderDelivery->statusid = 'IF';
        $orderDelivery->save();
    }

    /**
     * @param OrderDeliverie $orderDelivery
     *
     * @return void
     */
    public function SendEdiToRControl(OrderDeliverie $orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (in_array('SWH-21', (array) $parameter)) {
            EdiShipment::createEDIForEventToShipment($orderDelivery, 'DOCUMENT');
        }
    }
}
