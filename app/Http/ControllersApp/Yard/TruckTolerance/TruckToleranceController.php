<?php

namespace App\Http\ControllersApp\Yard\TruckTolerance;

use App\Http\Controllers\Controller;
use App\Models\Yard\ShipmentTruckTolerance;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class TruckToleranceController extends Controller
{
    use ResponseApi;

    public function getToleranceForTruckUnit(Request $request)
    {
        $tolerances = ShipmentTruckTolerance::where('truckid', $request['truckid'])
            ->select(['id', 'description', 'value01', 'value02'])
            ->get();
        return $this->getResponseSuccess('getToleranceForTruckUnit', $tolerances);
    }

}
