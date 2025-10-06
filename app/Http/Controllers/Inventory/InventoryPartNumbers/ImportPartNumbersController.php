<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\PartNumbers\ImportPartNumbers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportPartNumbersController extends BaseController
{
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
        Excel::import(new ImportPartNumbers, $file);

        return $this->successResponse('inventorypartnumbers.index');
    }
}
