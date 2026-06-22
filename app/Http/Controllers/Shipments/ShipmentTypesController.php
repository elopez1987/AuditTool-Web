<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\ShipmentType\CreateShipmentTypeRequest;
use App\Http\Requests\Web\Shipments\ShipmentType\UpdateShipmentTypeRequest;
use App\Models\Shipments\ShipmentTypes\ShipmentType;
use Illuminate\Http\Request;

class ShipmentTypesController extends BaseController
{
    public function index()
    {
        $this->authorize('shipmenttypes.index');

        return view('Shipments.ShipmentTypes.index');
    }

    public function getDataShipmentTypes(Request $request)
    {
        $data = ShipmentType::controlAccessUser()
            ->with(['status:statusid,name',
                'center:centerid,name,description',
                'warehouse:warehouseid,name'])
            ->select([
                'shipmenttypes.id',
                'shipmenttypes.centerid',
                'shipmenttypes.warehouseid',
                'shipmenttypes.name',
                'shipmenttypes.statusid',
            ])
            ->Name($request->input('name'));

        return $this->showDataToView($data, 'Shipments.ShipmentTypes.partials.buttons');
    }

    public function create()
    {
        $this->authorize('shipmenttypes.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Shipments.ShipmentTypes.create', compact('centers', 'warehouses'));
    }

    public function store(CreateShipmentTypeRequest $request)
    {
        ShipmentType::create($request->validated());

        return $this->redirectBackSuccess();
    }

    public function edit(ShipmentType $shipmenttype)
    {
        $this->authorize('shipmenttypes.edit');
        $status = $this->getStatusSTS();

        return view('Shipments.ShipmentTypes.edit', compact('shipmenttype', 'status'));
    }

    public function update(UpdateShipmentTypeRequest $request, ShipmentType $shipmenttype)
    {
        $shipmenttype->update($request->validated());

        return $this->redirectBackInfo();
    }
}
