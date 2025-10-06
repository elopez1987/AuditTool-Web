<?php

namespace App\Http\Controllers\Yard\ShipmentTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\ShipmentTruckTolerance;
use Illuminate\Http\Request;

class ShipmentTruckToleranceController extends BaseController
{
    public function index($id)
    {
        $this->authorize('shipmenttrucks.tolerance');
        $shipmenttruck = ShipmentTruck::find($id);
        $details = ShipmentTruckTolerance::where('truckid', $id)->get();

        return view('Yards.ShipmentTrucks.tolerance.index', compact('shipmenttruck', 'details'));
    }

    public function update(Request $request, $id)
    {
        $shipmenttruck = ShipmentTruck::find($id);
        ShipmentTruckTolerance::create([
            'centerid' => $shipmenttruck->centerid,
            'truckid' => $shipmenttruck->id,
            'description' => $request->description,
            'value01' => $request->value01,
            'value02' => $request->value02,
            'value03' => $request->value03,
            'value04' => $request->value04,
            'value05' => $request->value05,
        ]);

        return $this->redirectBackSuccessMessage('Registro agregado correctamente');
    }
}
