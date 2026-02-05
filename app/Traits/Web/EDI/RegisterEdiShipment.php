<?php

namespace App\Traits\Web\EDI;

use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;

trait RegisterEdiShipment
{
    //	Crear evento para Wms Izel
    public function addNewEdiForIzel($receiptunit, $type)
    {
        EdiShipment::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'shipmentid' => $receiptunit->id,
            'type' => 'IZEL',
            'client' => 'WMS - IZEL',
            'auxiliary01' => $type,
            'auxiliary02' => get_class($receiptunit),
            'auxiliary03' => '', // Payload
            'auxiliary05' => 'Pending',
        ]);
    }

    //    -----------Recurso confiable -----------------------------
    private function sendDataToRControl($receiptunit, $parameter, $typeEvent): void
    {
        if ($receiptunit->razonstatusid === 1) {
            if (in_array('SWH-21', (array) $parameter)) {
                $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $receiptunit->id)
                    ->whereNotIn('statusid', ['CA', 'DH'])->first();
                if (! empty($shipment)) {
                    self::createEDIForEventToShipmentForRControl($shipment, $typeEvent);
                }
            }
        }
    }

    //    Crea el registro de envío del evento del embarque (Entrada/Inicio y fin de proceso, facturacion y salida)
    public static function createEDIForEventToShipmentForRControl($model, $event)
    {
        EdiShipment::updateOrCreate([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'shipmentid' => $model->shipmentid,
            'type' => 'EVENT',
            'client' => 'RCONTROL',
            'auxiliary01' => $event,
            'auxiliary02' => get_class($model),
            'auxiliary05' => 'Pending',
        ]);
    }

    //    Crea el registro de envío de plan de carga del módulo de embarques
    public static function createEDIForPlanToShipmentForRControl($model)
    {
        EdiShipment::updateOrCreate([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'shipmentid' => $model->shipmentid,
            'type' => 'PLAN',
            'client' => 'RCONTROL',
            'auxiliary01' => 'PLAN',
            'auxiliary02' => get_class($model),
            'auxiliary05' => 'Pending',
        ]);
    }
}
