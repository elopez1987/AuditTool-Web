<?php

namespace App\Http\Controllers\Yard\ReceiptProviders;

use App\Exports\Yard\Catalogs\ExportProviderTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Yard\Catalogs\ImportProviders;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportProvidersController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportProviderTemplate,
            'template_import_providers.xlsx'
        );
    }

    public function index(Request $request)
    {
        $archivo = $request->file('file');
        Excel::import(new ImportProviders, $archivo);

        return $this->successResponse('receiptproviders.index');
    }
}
