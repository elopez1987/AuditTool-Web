<?php

namespace App\Http\ControllersApp\Yard\CheckList\Quality;

use App\Http\Controllers\Controller;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class CheckListQualityController extends Controller
{
    use ResponseApi;
    //     Consulta de detalle de puntos a validar para check list de calidad
    public function index(Request $request)
    {
        $checklist = StorageCheckList::controlAccessUser()->where('type', 'QI')
            ->where('statusid', '=', 'E')->first();
        $details = StorageCheckListDetail::controlAccessUser()->where('checklistid', $checklist->id)
            ->where('statusid', '=', 'E')
            ->select(['checklistid', 'id', 'description'])
            ->get();
        return $this->getResponseSuccess('getCheckListQI', $details);
    }



}
