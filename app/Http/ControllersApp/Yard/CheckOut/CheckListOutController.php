<?php

namespace App\Http\ControllersApp\Yard\CheckOut;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class CheckListOutController extends Controller
{
    use ResponseApi;

//    Lista los puntos del check list de salida
    public function getCheckListForOutUnit(Request $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('getCheckListForReceiptUnitIn',
                'No existen datos del folio ingresado o se encuentra es un estatus invalido');
        }
        $checkListId = StorageCheckList::where('centerid', $receiptunit->centerid)
            ->where('type', '=', 'OUT')->value('id');
        $results = StorageCheckListDetail::where('centerid', $receiptunit->centerid)
            ->where('checklistid', $checkListId)
            ->where('statusid','E')
            ->select(['checklistid', 'id', 'description'])
            ->get();
        return $this->getResponseSuccess('getCheckListForReceiptUnitIn', $results);
    }
}
