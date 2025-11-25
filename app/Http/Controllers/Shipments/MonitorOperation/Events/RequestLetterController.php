<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use Illuminate\Support\Facades\DB;
use Log;

class RequestLetterController extends BaseController
{
    public function requestletter($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
            $statusold = $orderDelivery->statusid;
            $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
            $shipment->requestletter = now();
            $shipment->save();

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-12', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse($e->getMessage());
        }

    }

    public function responseletter($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
            $statusold = $orderDelivery->statusid;
            $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
            $shipment->responseletter = now();
            $shipment->save();
            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-13', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse($e->getMessage());
        }
    }
}
