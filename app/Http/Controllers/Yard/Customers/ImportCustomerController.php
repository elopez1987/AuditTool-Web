<?php

namespace App\Http\Controllers\Yard\Customers;

use App\Exports\Yard\Catalogs\ExportOrderCustomerTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportOrderCustomer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportCustomerController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportOrderCustomerTemplate,
            'template_import_order_customers.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    public function importcustomer(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportOrderCustomer, $file);

        return $this->successResponse('ordercustomers.index');
    }
}
