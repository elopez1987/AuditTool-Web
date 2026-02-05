<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Traits\Web\EDI\RegisterEdiShipment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinishLoadOrderDeliveryController extends BaseController
{
    use RegisterEdiShipment;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
            if (! $orderDelivery) {
                return $this->errorResponse('El embarque no existe');
            }

            $statusold = $orderDelivery->statusid;

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (! empty($parameter) && in_array('SWH-32', (array) $parameter)) {
                $folioNumber = ReceiptUnit::whereId($orderDelivery->folionumber)->first(['id', 'statusid', 'shipmentid']);
                if (empty($folioNumber)) {
                    return $this->errorResponse('El embarque no cuenta con unidad acoplada, favor de validar.');
                }
                $this->updateFolioNumber($folioNumber, $orderDelivery);
            }
            $this->updateShipmentDataOperation($orderDelivery);
            $this->sendEDITToRControl($orderDelivery);
            $this->updateShipmentHeader($orderDelivery);
            $this->updateOrderDelivery($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);
            DB::commit();

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-08', $statusold);

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateFolioNumber($folioNumber, $orderDelivery): void
    {
        $folioNumber->unitstatusid = '2';
        $folioNumber->statusid = 'FI';
        $folioNumber->finishdate = now();
        $folioNumber->shipmentid = $orderDelivery->shipmentid;
        $folioNumber->save();
    }

    private function updateShipmentDataOperation($orderDelivery): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $orderDelivery->id)->first();
        $shipment->endload = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }

    private function sendEDITToRControl($orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
            $this->createEDIForEventToShipmentForRControl($orderDelivery, 'END_LOAD', 'EVENT');
        }
    }

    private function updateShipmentHeader($orderDelivery): void
    {
        $shipmentHeader = ShipmentHeader::where('orderid', $orderDelivery->id)->first();
        $shipmentHeader->statusid = 'FO';
        $shipmentHeader->finishdate = now();
        $shipmentHeader->save();
        //      Libera el stage para el siguiente embarque
        StorageLocation::updateLocation($shipmentHeader->locationid, 'E', '');
    }

    private function updateOrderDelivery($orderdelivery): void
    {
        $orderdelivery->statusid = 'FO';
        $orderdelivery->save();
    }

    private function updateShipmentSeal($orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)->first();
        $shipmentseal->statusorderid = 'FO';
        $shipmentseal->save();
    }
}
