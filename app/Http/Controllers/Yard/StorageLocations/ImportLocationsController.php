<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Exports\Yard\Catalogs\ExportLocationTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportLocations;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportLocationsController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportLocationTemplate,
            'template_import_locations.xlsx'
        );
    }

    public function importlocations(Request $request)
    {
        Excel::import(new ImportLocations, $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
