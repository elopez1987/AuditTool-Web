<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\Incidences\ImportIncidenceJourney;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ImportIncidencesJourneyController extends BaseController
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }

        Excel::import(new ImportIncidenceJourney, $request->file('file'),
            '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->successResponse('Incidencias importadas correctamente.');
    }
}
