<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\ImportShipmentBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportBillingController extends BaseController
{
    public function index(Request $request)
    {
        ini_set('max_execution_time', 300);
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportShipmentBilling(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Registros procesados con exito.');
    }
}
