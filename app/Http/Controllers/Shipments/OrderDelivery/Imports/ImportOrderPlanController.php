<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\OrderImport;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportOrderPlanController extends BaseController
{
    public function index(\Illuminate\Http\Request $request)
    {
        ini_set('max_execution_time', 300);
        $validator = Validator::make($request->all(), ['file' => 'mimes:csv,txt']);
        if ($validator->fails()) {
            return $this->errorResponse('El archivo es incorrecto, favor de validar');
        }
        $file = $request->file('file');
        Excel::import(new OrderImport, $file);
        $data = LogImportFile::get();

        return view('Configuration.EdiLogs.LogImportFile.index', compact('data'));
    }
}
