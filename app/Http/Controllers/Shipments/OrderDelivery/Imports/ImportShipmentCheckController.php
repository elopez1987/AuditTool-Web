<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Imports;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\VerifyProcess\ShipmentCheckImport;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportShipmentCheckController extends BaseController
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        ini_set('max_execution_time', 300);
        $file = $request->file('file');
        DB::table('logimports')->delete();
        Excel::import(new ShipmentCheckImport, $file, '', \Maatwebsite\Excel\Excel::XLSX);
        $data = LogImportFile::get();

        return view('Configuration.EdiLogs.LogImportFile.index', compact('data'));
    }
}
