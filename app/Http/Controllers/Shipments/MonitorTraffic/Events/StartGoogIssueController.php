<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;

class StartGoogIssueController extends BaseController
{
    public function index(OrderDeliverie $id)
    {
        $order = $id;
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $order->id)
                ->lockForUpdate()->first();

            $shipmentheader = ShipmentHeader::whereOrderid($orderDelivery->id)->lockForUpdate()->first();
            if (! empty($shipmentheader)) {
                if ($shipmentheader->startgoodissuedate != null) {
                    return $this->errorResponse('El embarque ya cuenta con inicio de facturación.');
                }
            }

            $shipmentheader->statusid = 'IF';
            $shipmentheader->startgoodissuedate = now();
            $shipmentheader->save();

            $statusold = $orderDelivery->statusid;
            $this->updateShipmentSeal($orderDelivery);
            $this->updateOrderDelivery($orderDelivery);
            $this->SendEdiToRControl($orderDelivery);

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-10', $statusold);

            DB::commit();

            return $this->redirectBackSuccessMessage('Datos agregados correctamente');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse([$e->getMessage()]);
        }
    }

    public function updateShipmentSeal(OrderDeliverie $orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($orderDelivery->id)->lockForUpdate()->first();
        $shipmentseal->statusorderid = 'IF';
        $shipmentseal->save();
    }

    public function updateOrderDelivery(OrderDeliverie $orderDelivery): void
    {
        $orderDelivery->statusid = 'IF';
        $orderDelivery->save();
    }

    public function SendEdiToRControl(OrderDeliverie $orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (in_array('SWH-21', (array) $parameter)) {
            EdiShipment::createEDIForEventToShipment($orderDelivery, 'DOCUMENT');
        }
    }
}
