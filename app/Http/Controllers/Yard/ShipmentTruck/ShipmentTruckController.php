<?php

namespace App\Http\Controllers\Yard\ShipmentTruck;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ShipmentTruck\CreateTruckRequest;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use Illuminate\Http\Request;

class ShipmentTruckController extends BaseController
{
    public function index()
    {
        $this->authorize('shipmenttrucks.index');
        return view('Yards.ShipmentTrucks.index');
    }

    public function getDataTrucks(Request $request)
    {
        $data = ShipmentTruck::controlAccessUser()
            ->with(['status', 'center'])
            ->Name($request->get('name'))->get();
        return $this->showDataToView($data, 'Yards.ShipmentTrucks.partials.buttons');
    }

    public function create()
    {
        $this->authorize('shipmenttrucks.create');
        $centers = $this->getCenterForSelect();
        return view('Yards.ShipmentTrucks.create', compact('centers'));
    }

    public function store(CreateTruckRequest $request)
    {
        ShipmentTruck::create($request->validated());
        return $this->successResponse('shipmenttrucks.index');
    }

    public function edit(ShipmentTruck $shipmenttruck)
    {
        $this->authorize('shipmenttrucks.edit');
        $status = $this->getStatusSTS();
        return view('Yards.ShipmentTrucks.edit', compact('shipmenttruck', 'status'));
    }

    public function update(ShipmentTruck $shipmenttruck, Request $request)
    {
        $shipmenttruck->update($request->all());
        return $this->infoResponse('shipmenttrucks.index');
    }

    public function destroy(ShipmentTruck $shipmenttruck)
    {
        $shipmenttruck->delete();
        return $this->successResponse('shipmenttrucks.index');
    }


}
