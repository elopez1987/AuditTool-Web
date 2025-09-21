<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportLocations;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportLocationsController extends BaseController
{
    public function importlocations(Request $request)
    {
        Excel::import(new ImportLocations(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
