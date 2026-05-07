<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartLoadOrderDeliveryController extends BaseController
{
    use RegisterEdiShipment;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->whereId($id)->lockForUpdate()->first();
            if (! $orderDelivery) {
                return $this->errorResponse('El embarque no existe');
            }

            if ($orderDelivery->statusid === 'IP') {
                return redirect()->back()->with('error', 'El evento ya fue ejecutado anteriormente.');
            }

            $statusold = $orderDelivery->statusid;

            $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
            if (! empty($parameter) && in_array('SWH-32', (array) $parameter)) {

                $folionumber = ReceiptUnit::whereId($orderDelivery->folionumber)->first();
                if (! $folionumber) {
                    return redirect()->back()->with(session()->flash('error', 'El embarque no cuenta con transporte,favor de validar.'));
                }
                if ($folionumber->zone->zoneid !== 'CO') {
                    return redirect()->back()->with(session()->flash('error', 'La caja no se encuentra en cortina, favor de enrampar.'));
                }
                $this->updateFolioNumber($folionumber);
                StorageUnitTrace::createTraceUnit($folionumber, '14');
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
        $orderDelivery->update([
            'statusid' => 'IP',
        ]);
    }

    private function updateFolioNumber($folionumber): void
    {
        $folionumber->update(
            ['statusid' => 'EP',
                'startdate' => now(),
            ]);
    }

    private function updateShipmentDataOperation(int $id): void
    {
        ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $id)
            ->update([
                'startload' => now(),
                'lastuserid' => Auth::id(),
            ]);
    }

    private function updateShipmentHeader(int $id): void
    {
        ShipmentHeader::where('orderid', $id)
            ->update(['statusid' => 'IP']);
    }

    private function updateShipmentSeal(int $id): void
    {
        ShipmentSeal::where('orderid', $id)
            ->update(['statusorderid' => 'IP']);
    }

    private function sendEDIToRControl($orderDelivery): void
    {
        $parameter = $this->getParametersOfWarehouse($orderDelivery->warehouseid);
        if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
            $this->createEDIForEventToShipmentForRControl($orderDelivery, 'START_LOAD', 'EVENT');
        }
    }
}
