<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Imports\Receipt\ImportDataReceipt;
use App\Models\Receipt\ReceiptAsn;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataReceiptController extends BaseController
{
    public function index(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:excel,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        ini_set('max_execution_time', 400);
        $file = $request->file('file');
        $receipt = ReceiptAsn::controlAccessUser()->findOrFail($id);
        Excel::import(new ImportDataReceipt($receipt), $file);
        return  $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }


}
