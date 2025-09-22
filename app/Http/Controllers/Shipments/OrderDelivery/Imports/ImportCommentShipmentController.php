<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\ImportCommentShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportCommentShipmentController extends BaseController
{
    public function importcommentshipment(Request $request)
    {
        ini_set('max_execution_time', 300);
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto. Solo se permiten archivos CSV.');
        }
        Excel::import(new ImportCommentShipment(), $request->file('file'), '', \Maatwebsite\Excel\Excel::CSV);
        return $this->redirectBackSuccessMessage('Datos importados con exito.');
    }
}
