<?php

namespace App\Http\Controllers\Yard\ShipmentTruck;

use App\Exports\Yard\Catalogs\ExportShipmentTruckTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportShipmentTrucks;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportShipmentTruckController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportShipmentTruckTemplate,
            'template_import_shipment_trucks.xlsx'
        );
    }

    public function importshipmenttrucks(Request $request)
    {
        $archivo = $request->file('file');
        Excel::import(new ImportShipmentTrucks, $archivo, \Maatwebsite\Excel\Excel::XLSX);

        return $this->successResponse('shipmenttrucks.index');
    }
}
