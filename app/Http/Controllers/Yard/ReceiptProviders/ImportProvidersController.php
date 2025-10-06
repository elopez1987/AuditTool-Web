<?php

namespace App\Http\Controllers\Yard\ReceiptProviders;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportProviders;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportProvidersController extends BaseController
{
    public function index(Request $request)
    {
        $archivo = $request->file('file');
        Excel::import(new ImportProviders, $archivo);

        return $this->successResponse('receiptproviders.index');
    }
}
