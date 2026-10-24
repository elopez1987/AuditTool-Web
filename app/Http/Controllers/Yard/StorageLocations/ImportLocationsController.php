<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Exports\Yard\Catalogs\ExportLocationTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportLocations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportLocationsController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportLocationTemplate,
            'template_import_locations.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function importlocations(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);

        Excel::import(new ImportLocations, $request->file('file'));

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
