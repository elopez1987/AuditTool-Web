<?php

namespace App\Http\Controllers\Shipments\OrderDelivery;

use App\Exports\Shipment\OrderDelivery\ExportTransportShipmentTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Shipments\OrderDelivery\ImportTransportShipment;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ImportTransportController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportTransportShipmentTemplate,
            'template_import_transport_shipment.xlsx'
        );
    }

    public function index(Request $request): View|RedirectResponse
    {
        ini_set('max_execution_time', 300);

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }

        try {
            Excel::import(
                new ImportTransportShipment,
                $request->file('file'),
                null,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'No se pudo procesar el archivo de importación: ' . $exception->getMessage()
            );
        }

        $data = LogImportFile::query()->orderByDesc('id')->get();

        return view('Configuration.EdiLogs.LogImportFile.index', compact('data'));
    }
}
