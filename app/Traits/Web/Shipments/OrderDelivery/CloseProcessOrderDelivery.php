<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderMonitor;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Yard\StorageLocation;
use Carbon\Carbon;

trait CloseProcessOrderDelivery
{
    public function closeOrderDelivery($id)
    {
        $order = self::cierreDeLaOrden($id);
        $header = self::cierreDeLasCabeceras($order);
        StorageLocation::updateLocation($header->locationid, 'E', '');
        self::cierreDelMonitor($order);
        self::createNewJourney($order);
        $this->createNewShipmentPod($order);

    }

    static function cierreDeLaOrden($id)
    {
        $order = OrderDeliverie::findOrFail($id);
        $order->statusid = 'DH';
        $order->save();
        return $order;
    }

    public function cierreDeLasCabeceras($order)
    {
        $header = ShipmentHeader::where('orderid', $order->id)
            ->first(['locationid', 'statusid']);
        $header->statusid = 'DH';
        $header->save();
        return $header;
    }

    public function cierreDelMonitor($order): void
    {
        $monitor = OrderMonitor::where('orderid', $order->id)
            ->first(['id', 'statusid']);
        $monitor->statusid = 'TM';
        $monitor->save();
    }

    public function createNewJourney($order): void
    {
        ShipmentJourney::create([
            'centerid' => $order->centerid,
            'warehouseid' => $order->warehouseid,
            'orderid' => $order->id,
        ]);
    }

    public function createNewShipmentPod($order)
    {
        ShipmentPod::updateOrCreate([
            'centerid' => $order->centerid,
            'warehouseid' => $order->warehouseid,
            'orderid' => $order->id,
        ], [
            'created_at' => Carbon::now(),
        ]);
    }


}
