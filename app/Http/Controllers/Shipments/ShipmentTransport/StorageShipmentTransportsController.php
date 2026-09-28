<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\ShipmentTransport\RegisterTransportRequest;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;
use DataTables;
use Illuminate\Http\Request;

class StorageShipmentTransportsController extends BaseController
{
    use QueryShipmentTransport;

    public function index()
    {
        $this->authorize('datatransport.index');
        $carriers = $this->getCarriersForSelect();

        return view('Shipments.StorageShipmenTransport.index', compact('carriers'));
    }

    public function getDataTransportShipment(Request $request)
    {
        $data = $this->getShipmentInProcess($request);

        return DataTables::of($data)
            ->addColumn('btn', 'Shipments.StorageShipmenTransport.partials.buttons')
            ->addColumn('transport', 'Shipments.StorageShipmenTransport.partials.statustransport')
            ->rawColumns(['btn', 'transport'])->toJson();
    }

    public function edit($id)
    {

        $transport = $this->getModelTransportEdit($id);

        return view('Shipments.StorageShipmenTransport.edit', compact('transport'));
    }

    public function update(RegisterTransportRequest $request, $id)
    {
        $transport = StorageShipmentTransport::where('id', $id)->first();
        if (is_null($transport)) {
            return $this->errorResponse('No existe el registro buscado, favor de validar.');
        }
        $transport->update($request->validated() + ['has_data' => 1]);

        return $this->successResponse('shipment-transport.index');
    }
}
