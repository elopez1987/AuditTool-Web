<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Shipments\OrderDelivery\Exception;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderMonitor;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CancelOrderDeliveryController extends BaseController
{
    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = self::cancelOrderDelivery($id);
            self::canceHeader($orderDelivery);
            self::canceShipmentSeal($orderDelivery);
            self::cancelOrdeMonitor($orderDelivery);
            self::cancelTransport($orderDelivery);
            self::cancelOrderDelivery($orderDelivery);
            DB::commit();

            return $this->successResponse('orderdeliveries.index');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse($error);
        }
    }

    private function cancelTransport($orderDelivery)
    {
        StorageShipmentTransport::where('orderid', $orderDelivery->id)
            ->first()
            ?->update([
                'operatorname' => '',
                'drivernumber' => '',
                'phone' => '',
                'boxnumber' => '',
                'trucknumber' => '',
                'has_data' => false,
                'has_registered' => false,
                'auxiliary05' => Uuid::uuid4()->toString(),
            ]);
    }

    private function cancelOrderDelivery($id)
    {
        return OrderDeliverie::controlAccessUser()->where('id', $id)
            ->select(['id', 'statusid', 'auxiliary05'])
            ->update(['statusid' => 'CA', 'auxiliary05' => Auth::user()->id, 'folionumber' => null]);
    }

    private function canceHeader($orderDelivery)
    {
        ShipmentHeader::controlAccessUser()->where('orderid', $orderDelivery->id)
            ->update(['statusid' => 'CA', 'cancellationdate' => now()]);
    }

    private function canceShipmentSeal($orderDelivery)
    {
        ShipmentSeal::whereOrderid($orderDelivery->id)
            ->update(['statusorderid' => 'CA']);
    }

    private function cancelOrdeMonitor($orderDelivery)
    {
        OrderMonitor::where('orderid', $orderDelivery->id)
            ->update(['statusid' => 'CN']);
    }
}
