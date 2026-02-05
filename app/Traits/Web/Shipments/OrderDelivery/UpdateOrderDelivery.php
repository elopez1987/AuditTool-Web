<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\EDI\RegisterEdiShipment;

trait UpdateOrderDelivery
{
    use RegisterEdiShipment;

    public function editOrderDelivery($orderDelivery, $request)
    {
        self::updateHeaderShipment($orderDelivery, $request);
        self::updateTransportShipment($orderDelivery, $request);
        self::createEDIToRControlEdit($orderDelivery);
    }

    private static function updateHeaderShipment($order, $request)
    {
        $header = ShipmentHeader::where('orderid', $order->id)->first();
        $header->totalweight = $request['totalweight'];
        $header->totalpieces = $request['totalpieces'];
        $header->totalcontainers = $request['totalcontainers'];
        $header->save();
    }

    private static function updateTransportShipment($order, $request)
    {
        $transport = StorageShipmentTransport::where('orderid', $order->id)->first(['id', 'carrierid']);
        if (! empty($transport)) {
            $transport->carrierid = $request['carrierid'];
            $transport->save();
        }
    }

    private static function createEDIToRControlEdit($orderdelivery)
    {
        $parameter = SystemWarehouse::whereWarehouseid($orderdelivery->warehouseid)->value('configurationflag');
        if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
            self::createEDIForEventToShipmentForRControl($orderdelivery, 'PLAN', 'PLAN');
        }
    }
}
