<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\General\StorageComment;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\ShipmentCheck;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\Shipments\OrderMonitor\CreateNewOrderMonitorShipment;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

trait CreateNewOrder
{
    use CreateNewOrderMonitorShipment;

    public function createNewOrder($shipment, $request, $type)
    {
        if ($request->has('comment')) {
            if ($request['comment'] !== '') {
                StorageComment::createCommentary('orderdeliveries', $shipment->id, $request);
            }
        }
        $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
        if ($type === 'Model') {
            self::createNewRegisterHeader($shipment, $request);
        }
        self::createNewRegisterSeal($shipment);
        self::createNewRegisterShipmentTransport($shipment);
        self::createShipmentDataOperation($shipment);
        self::createNewProcessCheck($shipment);
        self::createEDIToRControl($shipment, $parameter);
        if ($parameter && in_array('SWH-18', (array) $parameter)) {
            $this->createNewRegisterMonitor($shipment);
        }
    }

    public static function createEDIToRControl($shipment, $parameter)
    {
        if (in_array('SWH-21', (array) $parameter)) {
            EdiShipment::createEDIForPlanToShipment($shipment);
        }
    }

    public static function createNewRegisterHeader($shipment, $request)
    {
        ShipmentHeader::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'adddate' => $shipment->created_at,
                'totalweight' => $request['totalweight'],
                'totalcontainers' => $request['totalcontainers'],
                'totalpieces' => $request['totalpieces'],
            ],
        );
    }

    public static function createNewRegisterSeal($shipment)
    {
        ShipmentSeal::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'assignuser' => Auth::user()->id,
            ],
        );
    }

    public static function createNewRegisterShipmentTransport($shipment)
    {
        StorageShipmentTransport::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'carrierid' => $shipment->carrierid,
                'createdate' => $shipment->created_at,
                'auxiliary05' => Uuid::uuid4(),
                'createuser' => Auth::user()->id,
            ],
        );
    }

    public static function createShipmentDataOperation($shipment)
    {
        ShipmentDataOperation::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
        );
    }

    public static function createNewProcessCheck($shipment)
    {
        ShipmentCheck::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
        ]);
    }
}
