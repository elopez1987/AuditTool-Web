<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Exports\Shipment\OrderDelivery\ExportOrderPlanTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\OrderImportV2;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportOrderPlanController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportOrderPlanTemplate,
            'template_import_order_plan.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    public function index(Request $request)
    {
        ini_set('max_execution_time', 300);
        $validator = Validator::make($request->all(), ['file' => 'mimes:csv,txt']);
        if ($validator->fails()) {
            return $this->errorResponse('El archivo es incorrecto, favor de validar');
        }
        $file = $request->file('file');
        Excel::import(new OrderImportV2, $file);
        $data = LogImportFile::get();

        return view('Configuration.EdiLogs.LogImportFile.index', compact('data'));
    }
}
