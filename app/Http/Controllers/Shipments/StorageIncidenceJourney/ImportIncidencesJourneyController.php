<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Exports\Shipment\IncidencesJourneys\ExportIncidenceJourneyTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Shipments\Incidences\ImportIncidenceJourney;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Validator;

class ImportIncidencesJourneyController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportIncidenceJourneyTemplate,
            'template_import_incidence_journey.xlsx'
        );
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }

        Excel::import(
            new ImportIncidenceJourney,
            $request->file('file'),
            '',
            \Maatwebsite\Excel\Excel::XLSX
        );

        return $this->redirectBackSuccessMessage('Datos importados con exito.');
    }
}
