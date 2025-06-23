<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Imports\Shipments\Inverse\ImportRequestNCInverse;
use App\Imports\Shipments\Inverse\ImportRequestNEInverse;
use App\Imports\Shipments\Inverse\ImportRequestZareInverse;
use App\Imports\Shipments\Inverse\ImportResponseInventoryLogInverse;
use App\Imports\Shipments\Inverse\ImportResponseNCInverse;
use App\Imports\Shipments\Inverse\ImportResponseNEInverse;
use App\Imports\Shipments\Inverse\ImportResponseZareInverse;
use App\Imports\Shipments\Inverse\ImportWmsDateInverse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataInverseController extends BaseController
{

    public function importrequestzare(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportRequestZareInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importresponsezare(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportResponseZareInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importrequestne(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportRequestNEInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importresponsene(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportResponseNEInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importrequestnc(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportRequestNCInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importresponsenc(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportResponseNCInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importregisterwms(Request $request)
    {
        Excel::import(new ImportWmsDateInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }

    public function importresponseinventory(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto.');
        }
        Excel::import(new ImportResponseInventoryLogInverse(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->redirectBackSuccessMessage('Importación terminada con exito.');
    }


}
