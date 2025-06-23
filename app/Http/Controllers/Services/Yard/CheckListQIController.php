<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptUnit\SaveEvidenceForCheckListQIRequest;
use App\Models\Yard\CheckListTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckListDetail;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use Illuminate\Http\Request;

class CheckListQIController extends Controller
{
    use ResponseApi;
    use CheckListTransportTrait;
    use EvidenceFileImage;


//    Regresa la lista de puntos a validar en el check list para mostrar en la app
    public function getCheckListDetailQI(Request $request)
    {
        if ($request['folionumber'] == null) {
            return $this->getResponseWithErrors('getCheckListDetailQI', 'El número de folio o código de barra es obligatorio.');
        }
        $receiptunit = ReceiptUnit::searchForBarcode($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('getCheckListDetailQI', 'No existe registo con el valor ingresado.');
        }
        $checklistdetails = StorageCheckListDetail::whereCenterid($receiptunit->centerid)
            ->whereHas('checklist', function ($q) use ($receiptunit) {
                $q->where('type', 'QI');
                $q->where('statusid', 'E');
                $q->where('centerid', $receiptunit->centerid);
            })
            ->select(['checklistid', 'id', 'description'])
            ->get();
        return $this->getResponseSuccess('getCheckListDetailQI', $checklistdetails);
    }


//    Guarda el check list que se realiza
    public function saveCheckListQIForUnit(Request $request)
    {
        if ($request['folionumber'] == null) {
            return $this->getResponseWithErrors('getCheckListDetailQI','El número de folio es un valor obligatorio.');
        }
        $receiptunit = ReceiptUnit::controlAccessUser()->whereId($request['folionumber'])
            ->orWhere('barcode', $request['folionumber'])->whereNotIn('statusid',['TR'])->first();
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('getCheckListDetailQI', 'No existe registo con el valor ingresado.');
        }
        $totalCheckList = CheckListTransport::controlAccessUser()
            ->where('receiptunitid', $receiptunit->id)->where('type', 'QI')->select('id')->count();
        if ($totalCheckList > 0) {
            return $this->getResponseWithErrors('saveCheckListQIForUnit', 'Ya se ha registrado el check list de calidad.');
        }
        if (filled($request['shipmentid'])) {
            $receiptunit->shipmentid = $request['shipmentid'];
            $receiptunit->save();
        }
        $this->createCheckListToUnitV2($request, $receiptunit, 'QI');
        return $this->getResponseSuccess('saveCheckListQIForUnit', $receiptunit);
    }

//    Guarda la foto de la evidencia de calidad
    public function saveEvidenceForCheckListQI(SaveEvidenceForCheckListQIRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForBarcode($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('saveEvidenceForCheckListQI', 'No existe el folio ingresado.');
        }
        $this->createNewEvidence($request['file'],$receiptunit,'QI','ReceiptUnit','Yard','Quality',$request);
        return $this->getResponseSuccess('saveEvidenceForCheckListQI', '');
    }





}
