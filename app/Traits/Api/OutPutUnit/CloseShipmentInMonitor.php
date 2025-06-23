<?php

namespace App\Traits\Api\OutPutUnit;

use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;

trait CloseShipmentInMonitor
{
    public function closeShipmentInMonitor($receiptunit,$parameterApp,$request,$parameterWarehouse)
    {
        if ($receiptunit->razonstatusid == 1) {
            if ($receiptunit->unitstatusid == 2 && (empty($request['shipment']) || empty($request['seal']))) {
                return $this->getResponseWithErrors('registeroutunit', 'El número de embarque y sellos son valores obligatorios.');
            }
            $shipment = OrderDeliverie::searchForUnit($receiptunit->id);
            if (!empty($parameterApp) && in_array('APP-12', (array)$parameterApp)) {
                if (!empty($shipment)) {
                    if ($shipment->shipmentid != $request['shipment']) {
                        return $this->getResponseWithErrors('registeroutunit', 'El número de embarque no coincide con lo cargado en operaciones, favor de validar.');
                    }
                }
            }
            if (!empty($parameterWarehouse) && in_array('SWH-19', (array)$parameterWarehouse)) {
                if (!empty($shipment) && $shipment->statusid != 'DT') {
                    return $this->getResponseWithErrors('registeroutunit', 'El embarque no se encuentra terminado.');
                }
                if (!empty($shipment)) {
                    $shipment->statusid = 'DH';
                    $shipment->save();
                    if (!empty($parameterWarehouse) && in_array('SWH-21', (array)$parameterWarehouse)) {
                        EdiShipment::createEDIForEventToShipment($shipment, 'EXIT');
                    }
                }
            }
        }
    }

}
