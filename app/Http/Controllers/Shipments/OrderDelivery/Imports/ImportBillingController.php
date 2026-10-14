<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Exports\Shipment\OrderDelivery\ExportBillingShipmentTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\ImportShipmentBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportBillingController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportBillingShipmentTemplate,
            'template_import_billing_shipment.xlsx'
        );
    }

    public function index(Request $request)
    {
        ini_set('max_execution_time', 300);
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportShipmentBilling, $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->redirectBackSuccessMessage('Registros procesados con exito.');
    }
}
