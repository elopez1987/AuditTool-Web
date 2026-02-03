<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\ShipmentTransport\RegisterTransportRequest;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;

class UpdateFromMonitorOperationController extends BaseController
{
    use QueryShipmentTransport;

    public function updateDataTransportToShipment(RegisterTransportRequest $request, $id)
    {
        $transport = StorageShipmentTransport::where('orderid', $id)->first();
        $transport->operatorname = $request->operatorname;
        $transport->phone = $request->phone;
        $transport->drivernumber = $request->drivernumber;
        $transport->trucknumber = $request->trucknumber;
        $transport->boxnumber = $request->boxnumber;
        $transport->ecobox = $request->ecobox;
        $transport->ecotruck = $request->ecotruck;
        $transport->has_data = '1';

        $transport->save();

        return $this->redirectBackSuccess();
    }
}
