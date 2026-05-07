<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Jobs\Embarques\Correos\EnviarCorreoDocumentosEnviadosFacturacionJob;
use App\Jobs\Embarques\Mensajes\EnviarMensajeAFacturacionDocumentosEnviadosJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use App\Traits\Web\General\GetParameters;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDocumentOrderDeliveryController extends BaseController
{
    use GetParameters;

    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDeliveryOld = OrderDeliverie::controlAccessUser()->where('id', $id)->lockForUpdate()->first();

            if (! $orderDeliveryOld) {
                return $this->errorResponse('El embarque no existe');
            }

            if ($orderDeliveryOld->statusid === 'DO') {
                return $this->errorResponse('Los documentos ya fueron enviados anteriormente.');
            }

            $statusold = $orderDeliveryOld->statusid;
            $orderDelivery = $this->updateOrderDelivery($id);
            $this->updateShipmentDataOperation($orderDelivery);
            $this->updateShipmentHeader($orderDelivery);
            $this->updateShipmentSeal($orderDelivery);

            $parameterNotificationWarehouse = $this->getParameterNotificacionWarehouse($orderDelivery->warehouseid);
            if (! empty($parameterNotificationWarehouse) && in_array('MWH-02', (array) $parameterNotificationWarehouse)) {
                EnviarMensajeAFacturacionDocumentosEnviadosJob::dispatch($orderDelivery->id)->onQueue('yms-web');
            }
            if (! empty($parameterNotificationWarehouse) && in_array('MWH-09', (array) $parameterNotificationWarehouse)) {
                EnviarCorreoDocumentosEnviadosFacturacionJob::dispatch($orderDelivery->id)->onQueue('yms-web');
            }

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-09', $statusold);
            DB::commit();

            return $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse($e->getMessage());
        }
    }

    private function updateOrderDelivery($id): OrderDeliverie
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->lockForUpdate()->first();
        if (! empty($orderDelivery)) {
            $orderDelivery->statusid = 'DO';
            $orderDelivery->save();

            return $orderDelivery;
        }
    }

    private function updateShipmentDataOperation(OrderDeliverie $orderDelivery): void
    {
        ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $orderDelivery->id)->lockForUpdate()
            ->update([
                'senddocument' => now(),
                'lastuserid' => Auth::id(),
            ]);
    }

    private function updateShipmentHeader(OrderDeliverie $orderDelivery): void
    {
        ShipmentHeader::whereOrderid($orderDelivery->id)->lockForUpdate()
            ->update(['statusid' => 'DO']);
    }

    private function updateShipmentSeal(OrderDeliverie $orderDelivery): void
    {
        ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)
            ->lockForUpdate()
            ->update(['statusorderid' => 'DO']);
    }
}
