<?php

namespace App\Traits\Web\EDI;

use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use Ramsey\Uuid\Uuid;

trait RegisterEdiShipment
{
    // ----------------------	Crear evento para Wms Izel ----------------------------------------------
    public function addNewEdiForIzel($receiptunit, $type)
    {
        EdiShipment::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'shipmentid' => $receiptunit->id,
            'reference_number' => $receiptunit->id,
            'type' => 'IZEL',
            'external_system' => 'IZEL',
            'event_type' => $type,
            'transaction_id' => Uuid::uuid4(),
            'model_type' => get_class($receiptunit),
            'request_payload' => '', // Payload
            'document_type' => 'WebService',
            'status' => 'Pending',
        ]);
    }

    //   ------------------------------ Recurso confiable -------------------------------------------

    // ENTRANCE => Ingreso de unidad
    // START_LOAD => Inicio de carga
    // END_LOAD => Fin de carga
    // DOCUMENT => Inicio de facturacion
    // EXIT => Salida de unidad

    private function sendDataToRControl($receiptunit, $typeEvent, $type): void
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $receiptunit->id)
            ->whereNotIn('statusid', ['CA', 'DH'])->first();
        if (! empty($shipment)) {
            self::createEDIForEventToShipmentForRControl($shipment, $typeEvent, $type);
        }
    }

    //    Crea el registro de envío del evento del embarque
    public static function createEDIForEventToShipmentForRControl($model, $event, $type)
    {
        EdiShipment::updateOrCreate([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'shipmentid' => $model->shipmentid,
            'reference_number' => $model->id,
            'type' => $type,
            'external_system' => 'RCONTROL',
            'event_type' => $event,
            'transaction_id' => Uuid::uuid4(),
            'model_type' => get_class($model),
            'request_payload' => '', // Payload
            'document_type' => 'WebService',
            'status' => 'Pending',
        ]);
    }
}
