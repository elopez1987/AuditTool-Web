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
    public function index(int $id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()
            ->where('id', $id)
            ->first();

        if (! $orderDelivery) {
            return $this->errorResponse('El número de embarque no existe, favor de validar.');
        }

        if ($orderDelivery->statusid === 'IV') {
            return $this->errorResponse('El evento ya fue ejecutado anteriormente.');
        }

        try {
            DB::transaction(function () use ($id, $orderDelivery) {
                $statusOld = $orderDelivery->statusid;

                $this->updateShipmentDataOperation($id);
                $this->updateStatusOrderDelivery($orderDelivery);

                OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-03', $statusOld);
            });

            return $this->redirectBackSuccess();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateStatusOrderDelivery(OrderDeliverie $orderDelivery): void
    {
        $orderDelivery->update(['statusid' => 'IV']);
    }

    private function updateShipmentDataOperation(int $id): void
    {
        ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $id)
            ->update([
                'endchecker' => now(),
                'lastuserid' => Auth::id(),
            ]);
    }
}
