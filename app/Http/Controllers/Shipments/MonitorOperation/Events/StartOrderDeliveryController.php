<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\StorageLocation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartOrderDeliveryController extends BaseController
{
    public function index($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $orderdelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
            $statusold = $orderdelivery->statusid;
            $this->updateOrderDelivery($id);
            $shipment = $this->updateShipmentDataOperation($id);
            $this->updateShipmentHeader($id, $request['locationid']);
            $this->updateShipmentSeal($id);
            $this->BloquedLocationStage($shipment, $request['locationid']);

            OrderdeliveryTrace::saveEventShipment($orderdelivery, 'EEM-02', $statusold);

            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse($error);
        }
    }

    public function updateShipmentDataOperation($id): ShipmentDataOperation
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->startshipment = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();

        return $shipment;
    }

    public function updateShipmentHeader($id, $locationid): void
    {
        $header = ShipmentHeader::controlAccessUser()->where('orderid', $id)->first();
        $header->locationid = $locationid;
        $header->statusid = 'PR';
        $header->createuserid = Auth::user()->id;
        $header->save();
    }

    public function updateOrderDelivery($id): void
    {
        $orderdelivery = OrderDeliverie::whereId($id)->first();
        $orderdelivery->statusid = 'PR';
        $orderdelivery->save();
    }

    public function updateShipmentSeal($id): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($id)->first();
        $shipmentseal->statusorderid = 'PR';
        $shipmentseal->save();
    }

    public function BloquedLocationStage(ShipmentDataOperation $shipment, $locationid): void
    {
        $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
        if (in_array('SWH-20', (array) $parameter)) {
            StorageLocation::updateLocation($locationid, 'D', '');
        }
    }
}
