<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\ReceiptUnit;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartLoadOrderDeliveryController extends BaseController
{
    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
            $statusold = $orderDelivery->statusid;

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (! empty($parameter) && in_array('SWH-32', (array) $parameter)) {

                $folionumber = ReceiptUnit::whereId($orderDelivery->folionumber)->first(['zoneid', 'id']);
                if (! $folionumber) {
                    return redirect()->back()->with(session()->flash('error', 'El embarque no cuenta con transporte,favor de validar.'));
                }
                if ($folionumber->zone->zoneid !== 'CO') {
                    return redirect()->back()->with(session()->flash('error', 'La caja no se encuentra en cortina, favor de enrampar.'));
                }
                $this->updateFolioNumber($folionumber);
            }

            $this->updateOrderDelivery($orderDelivery);
            $this->updateShipmentDataOperation($id);
            $this->updateShipmentHeader($id);
            $this->updateShipmentSeal($id);
            $this->sendEDIToRControl($orderDelivery);

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-07', $statusold);

            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateOrderDelivery($orderDelivery): void
    {
        $orderDelivery->statusid = 'IP';
        $orderDelivery->save();
    }

    private function updateFolioNumber($folionumber): void
    {
        $folionumber->statusid = 'EP';
        $folionumber->startdate = now();
        $folionumber->save();
    }

    private function updateShipmentDataOperation($id): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->startload = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }

    private function updateShipmentHeader($id): void
    {
        $shipmentheader = ShipmentHeader::whereOrderid($id)->first();
        $shipmentheader->statusid = 'IP';
        $shipmentheader->save();
    }

    private function updateShipmentSeal($id): void
    {
        $shipmentseal = ShipmentSeal::whereOrderid($id)->first();
        $shipmentseal->statusorderid = 'IP';
        $shipmentseal->save();
    }

    private function sendEDIToRControl($orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
            EdiShipment::createEDIForEventToShipment($orderDelivery, 'START_LOAD');
        }
    }
}
