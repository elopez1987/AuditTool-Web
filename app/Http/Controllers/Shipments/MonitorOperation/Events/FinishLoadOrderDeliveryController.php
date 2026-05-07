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
use App\Models\Yard\StorageUnitTrace;
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
                return $this->errorResponse('El embarque no existe,favor de validar.');
            }

            $statusold = $orderDelivery->statusid;

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (! empty($parameter) && in_array('SWH-32', (array) $parameter)) {
                $folioNumber = ReceiptUnit::whereId($orderDelivery->folionumber)->first();
                if (empty($folioNumber)) {
                    return $this->errorResponse('El embarque no cuenta con unidad acoplada, favor de validar.');
                }
                $this->updateFolioNumber($folioNumber, $orderDelivery);
                StorageUnitTrace::createTraceUnit($folioNumber, '15');
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
        $folioNumber->update([
            'unitstatusid' => '2',
            'statusid' => 'FI',
            'finishdate' => now(),
            'shipmentid' => $orderDelivery->shipmentid,
        ]);
    }

    private function updateShipmentDataOperation($orderDelivery): void
    {
        ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $orderDelivery->id)
            ->update([
                'endload' => now(),
                'lastuserid' => Auth::id(),
            ]);
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
        $shipmentHeader = ShipmentHeader::where('orderid', $orderDelivery->id)->firstOrFail();

        $shipmentHeader->update([
            'statusid' => 'FO',
            'finishdate' => now(),
        ]);

        StorageLocation::updateLocation($shipmentHeader->locationid, 'E', '');
    }

    private function updateOrderDelivery($orderdelivery): void
    {
        $orderdelivery->update([
            'statusid' => 'FO',
        ]);
    }

    private function updateShipmentSeal($orderDelivery): void
    {
        ShipmentSeal::controlAccessUser()
            ->whereOrderid($orderDelivery->id)
            ->update(['statusorderid' => 'FO']);
    }
}
