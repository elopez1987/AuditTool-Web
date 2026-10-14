<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Exports\Inventory\PartNumbers\ExportPartNumbersTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Inventario\PartNumbers\ImportPartNumbers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportPartNumbersController extends BaseController
{
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportPartNumbersTemplate,
            'template_import_part_numbers.xlsx'
        );
    }

    public function importPartNumber(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:excel,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        ini_set('max_execution_time', 400);
        $file = $request->file('file');

        $import = new ImportPartNumbers;
        Excel::import($import, $file);

        $omitted = $import->getFailedRowsCount();
        if ($omitted > 0) {
            return redirect()->route('inventorypartnumbers.index')->with(
                'warning',
                "Importación finalizada. Se omitieron {$omitted} fila(s) por datos inválidos o faltantes; revise el log para más detalle."
            );
        }

        return $this->successResponse('inventorypartnumbers.index');
    }
}
