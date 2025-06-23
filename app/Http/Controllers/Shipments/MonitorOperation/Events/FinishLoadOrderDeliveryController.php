<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Shipments\OrderDelivery\Events\Exception;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinishLoadOrderDeliveryController extends BaseController
{
    public function index($id)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
            $folioNumber = ReceiptUnit::whereId($orderDelivery->folionumber)->first(['id', 'statusid', 'shipmentid']);
            if (empty($folioNumber)) {
                return $this->errorResponse('El embarque ya no cuenta con unidad acoplada, favor de validar.');
            }
            $this->updateFolioNumber($folioNumber, $orderDelivery);
            $this->updateShipmentDataOperation($orderDelivery);
            $this->sendEDITToRControl($orderDelivery);
            $this->updateShipmentHeader($orderDelivery);
            $this->updateOrderDelivery($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);
            DB::commit();
            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $folioNumber
     * @param $orderDelivery
     * @return void
     */
    public function updateFolioNumber($folioNumber, $orderDelivery): void
    {
        $folioNumber->unitstatusid = '2';
        $folioNumber->statusid = 'FI';
        $folioNumber->finishdate = now();
        $folioNumber->shipmentid = $orderDelivery->shipmentid;
        $folioNumber->save();
    }

    /**
     * @param $orderDelivery
     * @return void
     */
    public function updateShipmentDataOperation($orderDelivery): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $orderDelivery->id)->first();
        $shipment->endload = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }

    /**
     * @param $orderDelivery
     * @return void
     */
    public function sendEDITToRControl($orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (!empty($parameter) && in_array('SWH-21', (array)$parameter)) {
            EdiShipment::createEDIForEventToShipment($orderDelivery, 'END_LOAD');
        }
    }

    /**
     * @param $orderDelivery
     * @param $shipmentheader
     * @return void
     */
    public function updateShipmentHeader($orderDelivery): void
    {
        $shipmentHeader = ShipmentHeader::where('orderid', $orderDelivery->id)->first();
        $shipmentHeader->statusid = 'FO';
        $shipmentHeader->finishdate = now();
        $shipmentHeader->save();
//      Libera el stage para el siguiente embarque
        StorageLocation::updateLocation($shipmentHeader->locationid, 'E', '');
    }

    /**
     * @param $orderdelivery
     * @return void
     */
    public function updateOrderDelivery($orderdelivery): void
    {
        $orderdelivery->statusid = 'FO';
        $orderdelivery->save();
    }

    /**
     * @param $orderDelivery
     * @return void
     */
    public function updateShipmentSeal($orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)->first();
        $shipmentseal->statusorderid = 'FO';
        $shipmentseal->save();
    }


}
