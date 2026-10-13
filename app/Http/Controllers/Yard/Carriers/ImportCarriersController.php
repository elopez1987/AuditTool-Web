<?php

namespace App\Http\Controllers\Yard\Carriers;

use App\Exports\Yard\Catalogs\ExportCarrierTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportCarriers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportCarriersController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportCarrierTemplate,
            'template_import_carriers.xlsx'
        );
    }

    public function importcarriers(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportCarriers, $file);

        return $this->successResponse('shipmentcarriers.index');
    }
}
