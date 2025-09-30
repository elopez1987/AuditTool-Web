<?php

namespace App\Traits\Web\Izel;

use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;

trait RegisterEdiShipment
{
    public function addNewEdiForIzel($receiptunit, $parameter, $type)
    {
        if (in_array('SWH-08', (array) $parameter)) {
            EdiShipment::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'shipmentid' => $receiptunit->id,
                'type' => 'IZEL',
                'auxiliary01' => $type,
            ]);
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
            'auxiliary01' => $event,
            'client' => 'RCONTROL',
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
}
