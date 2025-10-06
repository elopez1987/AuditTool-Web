<?php

namespace App\Http\Controllers\Yard\ShipmentTruck;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportShipmentTrucks;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportShipmentTruckController extends BaseController
{
    public function importshipmenttrucks(Request $request)
    {
        $archivo = $request->file('file');
        Excel::import(new ImportShipmentTrucks, $archivo, \Maatwebsite\Excel\Excel::XLSX);

        return $this->successResponse('shipmenttrucks.index');
    }
}
