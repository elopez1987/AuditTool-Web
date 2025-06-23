<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaveEvidenceInProcessController extends Controller
{
    use ResponseApi;

//    use EvidencePhotoTrait;
    use EvidenceFileImage;


    //    Evidencias de proceso de carga o descarga desde el módulo de movimientos de unidad
    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['file'] != null) {
                $receiptUnit = ReceiptUnit::searchForId($request['folioid']);
                $this->createNewEvidence($request['file'], $receiptUnit, 'IP', 'ReceiptUnit', 'Yard', 'InProcess', $request);
            } else {
                return $this->getResponseWithErrors('uploadEvidenceInProcessUnit', 'La foto es un valor obligatorio.');
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('uploadEvidenceInProcessUnit', '');
        } else {
            return $this->getResponseWithErrors('uploadEvidenceInProcessUnit', 'Error al procesar la solicitud,favor de intentarlo nuevamente');
        }
    }
}
