<?php

namespace App\Http\Controllers\Yard\StorageCheckLists\Imports;

use App\Http\Controllers\BaseController;
use App\Imports\Yard\CheckList\ImportCheckListDetails;
use App\Models\Yard\StorageCheckList;
use App\Traits\Web\General\Responser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportCheckListDetailsController extends BaseController
{
    use Responser;

    public function index(Request $request)
    {
        $checkList = StorageCheckList::findOrFail($request['checklistid']);
        Excel::import(new ImportCheckListDetails($checkList), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
