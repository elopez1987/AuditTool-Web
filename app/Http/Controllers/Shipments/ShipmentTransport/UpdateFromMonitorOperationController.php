<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\ShipmentTransport\RegisterTransportRequest;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;
use Illuminate\Http\Request;

class UpdateFromMonitorOperationController extends BaseController
{

    use QueryShipmentTransport;


    public function updateDataTransportToShipment(RegisterTransportRequest $request, $id)
    {
        $transport = StorageShipmentTransport::where('orderid', $id)->first();
        $transport->update($request->only('operatorname', 'phone', 'drivernumber',
            'truckid', 'boxnumber', 'auxiliary03', 'auxiliary04', 'trucknumber') + ['auxiliary02' => '1']);
        return $this->redirectBackSuccess();
    }


}
