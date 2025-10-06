<?php

namespace App\Http\Controllers\Yard\Customers;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportOrderCustomer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportCustomerController extends BaseController
{
    public function importcustomer(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportOrderCustomer, $file);

        return $this->successResponse('ordercustomers.index');
    }
}
