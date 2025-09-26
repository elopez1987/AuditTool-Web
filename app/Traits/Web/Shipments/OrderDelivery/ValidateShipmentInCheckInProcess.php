<?php

namespace App\Traits\Web\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;

trait ValidateShipmentInCheckInProcess
{
	public function validateShipmentInCheckIn($receiptunit)
	{
		$shipment = OrderDeliverie::validateShipmentOrder($receiptunit->shipmentid);
		if ($shipment === null) {
			return redirect()->back()->withInput()->with(session()->flash('error', 'El número de embarque no se encuentra programado, favor de validar.'));
		}
		if ($shipment->carrierid !== $receiptunit->carrierid) {
			return redirect()->back()->withInput()->with(session()->flash('error', 'La linea de transporte es diferente a la planeada,favor de validar.'));
		}
	}


}
