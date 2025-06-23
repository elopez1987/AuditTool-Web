<?php

namespace App\Http\ControllersApp\Yard\CheckList\CheckIn;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use Illuminate\Http\Request;

class SearchCheckListDetailToCheckInController extends BaseController
{
    use ResponseApi;
    use CheckListTransportTrait;

    //    Consulta los puntos del check list a realizar en proceso de entrada
    public function index(Request $request)
    {
        if (empty($request['folionumber'])) {
            return $this->getResponseWithErrors('getCheckListForReceiptUnitIn', 'El número de folio es obligatorio');
        }
        $receiptunit = $this->getReceiptUnitStatusES($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('getCheckListForReceiptUnitIn',
                'No existen datos del folio ingresado o se encuentra es un estatus invalido');
        }
        if ($receiptunit->centerid == 'HENKELTOL01') {
            if ($receiptunit->razonstatusid == '1') {
                $checklistdetails = $this->getDetailCheckList('3');
                $checkList = StorageCheckList::controlAccessUser()->where('id', '3')->first();
            } else {
                $checklistdetails = $this->getDetailCheckList('10018');
                $checkList = StorageCheckList::controlAccessUser()->where('id', '10018')->first();
            }
            $checklistdetails->prepend(['typeCheck' => $checkList->auxiliary02,]);
            return $this->getResponseSuccess('getCheckListForReceiptUnitIn', $checklistdetails);
        }

        $checklistdetails = $this->getDetailCheckListForOrigen($receiptunit);
        $origen = $receiptunit->unittype->auxiliary01;
        $checkList = StorageCheckList::controlAccessUser()->where('statusid', 'E')
            ->where('type', 'IN')
            ->where('auxiliary01', $origen)->first();
        $checklistdetails->prepend(['typeCheck' => $checkList->auxiliary02,]);
        return $this->getResponseSuccess('getCheckListForReceiptUnitIn', $checklistdetails);
    }

    public function getReceiptUnitStatusES($folionumber)
    {
        return ReceiptUnit::controlAccessUser()->where('statusid', '=', 'ES')
            ->where('id', '=', $folionumber)->first();
    }

    public function getDetailCheckListForOrigen($receiptunit)
    {
        $origen = $receiptunit->unittype->auxiliary01;
        return StorageCheckListDetail::whereCenterid($receiptunit->centerid)
            ->whereHas('checklist', function ($q) use ($receiptunit, $origen) {
                $q->where('type', 'IN');
                $q->where('statusid', 'E');
                $q->where('auxiliary01', $origen);
                $q->where('centerid', $receiptunit->centerid);
            })
            ->where('statusid', 'E')
            ->get(['id', 'description', 'checklistid']);
    }

    public function getDetailCheckList($checklistid)
    {
        return StorageCheckListDetail::controlAccessUser()
            ->where('checklistid', '=', $checklistid)
            ->where('statusid', '=', 'E')
            ->get(['id', 'description', 'checklistid']);
    }


}
