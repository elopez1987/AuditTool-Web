<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartOrderDeliveryController extends BaseController
{
    public function index($id, Request $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $this->updateOrderDelivery($id);
            $shipment = $this->updateShipmentDataOperation($id);
            $this->updateShipmentHeader($id, $request['locationid']);
            $this->updateShipmentSeal($id);
            $this->BloquedLocationStage($shipment, $request['locationid']);
            DB::commit();
            $success = true;
            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse($error);
        }
    }

    /**
     * @param $id
     * @return ShipmentDataOperation
     */
    public function updateShipmentDataOperation($id): ShipmentDataOperation
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->startshipment = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
        return $shipment;
    }

    /**
     * @param $id
     * @param $locationid
     * @return void
     */
    public function updateShipmentHeader($id, $locationid): void
    {
        $header = ShipmentHeader::controlAccessUser()->where('orderid', $id)->first();
        $header->locationid = $locationid;
        $header->statusid = 'PR';
        $header->createuserid = Auth::user()->id;
        $header->save();
    }

    /**
     * @param $id
     * @return void
     */
    public function updateOrderDelivery($id): void
    {
        $orderdelivery = OrderDeliverie::whereId($id)->first();
        $orderdelivery->statusid = 'PR';
        $orderdelivery->save();
    }

    /**
     * @param $id
     * @return void
     */
    public function updateShipmentSeal($id): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($id)->first();
        $shipmentseal->statusorderid = 'PR';
        $shipmentseal->save();
    }

    /**
     * @param ShipmentDataOperation $shipment
     * @param $locationid
     * @return void
     */
    public function BloquedLocationStage(ShipmentDataOperation $shipment, $locationid): void
    {
        $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
        if (in_array('SWH-20', (array)$parameter)) {
            StorageLocation::updateLocation($locationid, 'D', '');
        }
    }


}
