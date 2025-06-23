<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Shipments\OrderDelivery\Exception;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderMonitor;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            DB::commit();
            return $this->successResponse('orderdeliveries.index');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse($error);
        }
    }

    static function cancelOrderDelivery($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)
            ->select(['id', 'statusid', 'auxiliary05'])
            ->first();
        $orderDelivery->statusid = 'CA';
        $orderDelivery->auxiliary05 = Auth::user()->id;
        $orderDelivery->save();
        return $orderDelivery;
    }

    static function canceHeader($orderDelivery)
    {
        $header = ShipmentHeader::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $header->statusid = 'CA';
        $header->cancellationdate = now();
        $header->save();
    }

    static function canceShipmentSeal($orderDelivery)
    {
        $shipmentseal = ShipmentSeal::whereOrderid($orderDelivery->id)->first();
        $shipmentseal->statusorderid = 'CA';
        $shipmentseal->save();
    }

    static function cancelOrdeMonitor($orderDelivery)
    {
        $ordermonitor = OrderMonitor::where('orderid', $orderDelivery->id)->first();
        $ordermonitor->statusid = 'CN';
        $ordermonitor->save();
    }

}
