<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Jobs\Embarques\Correos\CorreoSolicitudCartaPorteJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use Illuminate\Support\Facades\DB;
use Log;

class RequestLetterController extends BaseController
{
    public function requestletter($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->lockForUpdate()->first();
            $statusold = $orderDelivery->statusid;
            $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->lockForUpdate()->first();

            if (! $shipment) {
                throw new \Exception('No existe información de operación para este envío.');
            }

            if (! is_null($shipment->requestletter)) {
                throw new \Exception('El evento ya fue ejecutado anteriormente.');
            }

            $shipment->requestletter = now();
            $shipment->save();

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-12', $statusold);

            $parameterNotificationWarehouse = $this->getParameterNotificacionWarehouse($orderDelivery->warehouseid);
            if (! empty($parameterNotificationWarehouse) && in_array('MWH-11', (array) $parameterNotificationWarehouse)) {
                CorreoSolicitudCartaPorteJob::dispatch($orderDelivery);
            }

            DB::commit();

            return $this->redirectBackSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse($e->getMessage());
        }

    }

    public function responseletter($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->lockForUpdate()->first();
            $statusold = $orderDelivery->statusid;
            $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->lockForUpdate()->first();
            $shipment->responseletter = now();
            $shipment->save();
            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-13', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse($e->getMessage());
        }
    }
}
