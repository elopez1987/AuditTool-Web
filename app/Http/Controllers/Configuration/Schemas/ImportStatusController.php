<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Imports\Configuration\StatusImport;
use App\Models\Configuration\Schemas\SystemSchema;
use Excel;
use Illuminate\Http\Request;
use Validator;

class ImportStatusController extends BaseController
{
    public function index(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:excel,xlsx',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        ini_set('max_execution_time', 400);
        $file = $request->file('file');
        $schema = SystemSchema::where('schemaid', $id)->first();
        Excel::import(new StatusImport($schema), $file);

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
