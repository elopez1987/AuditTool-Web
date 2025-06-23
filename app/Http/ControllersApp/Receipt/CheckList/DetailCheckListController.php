<?php

namespace App\Http\ControllersApp\Receipt\CheckList;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\CheckList\CheckListDetailRequest;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use App\Traits\ResponseApi;

class DetailCheckListController extends Controller
{
    use ResponseApi;

    public function getDetailCheckListForReceipt(CheckListDetailRequest $request)
    {
        $checklist = $this->getCheckListForType('REC');
        $details = $this->getCheckListDetailForType($checklist->id, $request['type']);
        if (!empty($details)) {
            return $this->getResponseSuccess('getCheckListForReceiptInProcess', $details);
        }else{
            return $this->getResponseSuccessWithMessage('getCheckListForReceiptInProcess',[], 'No existen registros con el filtro seleccionado.','200');
        }
    }

    private function getCheckListForType($type)
    {
        return StorageCheckList::controlAccessUser()->where('type', '=', $type)
            ->where('statusid', '=', 'E')->first(['id', 'name']);
    }

    private function getCheckListDetailForType($checklistid, $type)
    {
        return StorageCheckListDetail::controlAccessUser()->whereChecklistid($checklistid)
            ->where('auxiliary01', '=', $type)
            ->where('statusid', 'E')
            ->select(['id', 'description', 'checklistid'])->get();
    }

}
