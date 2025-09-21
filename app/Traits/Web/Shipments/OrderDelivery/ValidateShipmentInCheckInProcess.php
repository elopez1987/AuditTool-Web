<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;

trait ValidateShipmentInCheckInProcess
{
    public function validateShipmentInCheckIn($request, $receiptunit, $parameter, $platform)
    {
        if ($request['razonstatusid'] === '1') {
            if (! empty($parameter) && in_array('SWH-01', (array) $parameter)) {
                $shipment = OrderDeliverie::validateShipmentOrder($receiptunit->shipmentid);
                if ($shipment === null) {
                    if ($platform === 'WEB') {
                        return redirect()->back()->withInput()
                            ->with(session()->flash('error', 'El número de embarque no se encuentra programado, favor de validar.'));
                    }
                }
                if ($shipment->carrierid !== $receiptunit->carrierid) {
                    if ($platform === 'WEB') {
                        return redirect()->back()->withInput()
                            ->with(session()->flash('error', 'La linea de transporte es diferente a la planeada,favor de validar.'));
                    }
                }
                $shipment->folionumber = $receiptunit->id;
                $shipment->save();
                self::updateStatusOfTransportInShipment($shipment);
            }
        }
    }

    public static function updateStatusOfTransportInShipment($shipment)
    {
        $shipmenttransport = StorageShipmentTransport::controlAccessUser()->where('orderid', $shipment->id)->first();
        if (! empty($shipmenttransport)) {
            $shipmenttransport->auxiliary01 = '1';
            $shipmenttransport->save();
        }
    }
}
