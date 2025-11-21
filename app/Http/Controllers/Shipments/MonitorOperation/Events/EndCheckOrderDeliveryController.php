<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EndCheckOrderDeliveryController extends BaseController
{
    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderdelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
            $statusold = $orderdelivery->statusid;
            $this->updateShipmentDataOperation($id);
            $this->updateStatusOrderDelivery($id);
            OrderdeliveryTrace::saveEventShipment($orderdelivery, 'EEM-03', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateStatusOrderDelivery($id): void
    {
        $orderdelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
        $orderdelivery->statusid = 'IV';
        $orderdelivery->save();
    }

    public function updateShipmentDataOperation($id): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->endchecker = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }
}
