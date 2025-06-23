<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;

trait UpdateOrderDelivery
{
    public function editOrderDelivery($orderDelivery, $request)
    {
        self::updateHeaderShipment($orderDelivery, $request);
        self::updateTransportShipment($orderDelivery, $request);
        self::createEDIToRControlEdit($orderDelivery);
    }

    static function updateHeaderShipment($order, $request)
    {
        $header = ShipmentHeader::where('orderid', $order->id)->first();
        $header->totalweight = $request['totalweight'];
        $header->totalpieces = $request['totalpieces'];
        $header->totalcontainers = $request['totalcontainers'];
        $header->save();
    }

    static function updateTransportShipment($order, $request)
    {
        $transport = StorageShipmentTransport::where('orderid', $order->id)->first(['id', 'carrierid']);
        if (!empty($transport)) {
            $transport->carrierid = $request['carrierid'];
            $transport->save();
        }
    }

    static function createEDIToRControlEdit($orderdelivery)
    {
        $parameter = SystemWarehouse::whereWarehouseid($orderdelivery->warehouseid)->value('configurationflag');
        if (in_array('SWH-21', (array)$parameter)) {
            EdiShipment::createEDIForPlanToShipment($orderdelivery);
        }
    }


}
