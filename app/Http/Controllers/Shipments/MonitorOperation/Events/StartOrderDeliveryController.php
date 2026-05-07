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
            $orderdelivery = OrderDeliverie::controlAccessUser()->whereId($id)->lockForUpdate()->first();
            if ($orderdelivery->statusid === 'PR') {
                DB::rollBack();

                return $this->errorResponse('El evento ya fue ejecutado anteriormente.');
            }

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

        $shipment->update(
            ['startshipment' => now(), 'lastuserid' => Auth::id()],
        );

        return $shipment;
    }

    public function updateShipmentHeader($id, $locationid): void
    {
        ShipmentHeader::controlAccessUser()->where('orderid', $id)
            ->update(
                [
                    'locationid' => $locationid,
                    'statusid' => 'PR',
                    'createuserid' => Auth::id(),
                ],
            );
    }

    public function updateOrderDelivery($id): void
    {
        OrderDeliverie::whereId($id)
            ->update([
                'statusid' => 'PR',
            ]);
    }

    public function updateShipmentSeal($id): void
    {
        ShipmentSeal::whereOrderid($id)
            ->update(['statusorderid' => 'PR']);
    }

    public function BloquedLocationStage(ShipmentDataOperation $shipment, $locationid): void
    {
        $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
        if (! empty($parameter) && in_array('SWH-20', (array) $parameter)) {
            StorageLocation::updateLocation($locationid, 'D', '');
        }
    }
}
