<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class CheckListEvidencePhotosProcessController extends Controller
{
    use ResponseApi;

    public function getListEvidencePhotos(Request $request)
    {
        $folioNumber = $request['folionumber'];
        if (empty($folioNumber)) {
            return $this->getResponseWithErrors('getListEvidencePhotos', 'El número de folio es requerido.');
        }
        $receiptUnit = ReceiptUnit::searchForId($folioNumber);
        if (empty($receiptUnit)) {
            return $this->getResponseWithErrors('getListEvidencePhotos', 'El número de folio no existe favor de validar.');
        }
        $listPhotos = $this->fetchPhotos($receiptUnit->id, $receiptUnit->razonstatusid, $receiptUnit->centerid);
        return $this->getResponseSuccess('getListEvidencePhotos', $listPhotos);
    }

    private function fetchPhotos($receiptId, $reasonId, $centerid)
    {
        $receiptComments = [
            1 => 'SH',
            2 => 'RE',
            17 => 'DE'
        ];
        $comment = $receiptComments[$reasonId] ?? null;
        return  SystemStatus::select(['statusid', 'name'])
            ->where('schemaid', 'FIP')
            ->where('comment', $comment)
            ->where('auxiliary02', $centerid)
            ->whereNotIn('statusid', function($query) use ($receiptId) {
                $query->select('auxiliary04')
                    ->from((new EvidencePhoto)->getTable())
                    ->where('model_id', $receiptId)
                    ->where('model_type', 'ReceiptUnit')
                    ->where('auxiliary01', 'IP');
            })->get();
    }


}
