<?php

namespace App\Http\Controllers\Yard\Carriers;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportCarriers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportCarriersController extends BaseController
{
    public function importcarriers(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportCarriers, $file);

        return $this->successResponse('shipmentcarriers.index');
    }
}
