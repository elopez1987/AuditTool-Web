<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\ResponseApi;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EvidenceReceiptUnitController extends Controller
{

    use ResponseApi;
    use SavePhotoInRegister;

    public function saveEvidenceInProcessForList(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request['file'] ?? '';
            $folionumber = $request['folionumber'] ?? '';
            if ($file === '' || $folionumber === '') {
                return $this->getResponseWithErrors('saveEvidenceInProcessForList', 'No se ha adjuntado ningún archivo.');
            }
            $receiptUnit = ReceiptUnit::searchForId($folionumber);
            if (empty($receiptUnit)) {
                return $this->getResponseWithErrors('saveEvidenceInProcessForList', 'No existe el número de folio buscado.');
            }
            $path = $this->createNewEvidenceForRegister($file, $receiptUnit, 'Yard', 'ReceiptUnit', 'InProcess');
            $this->saveEvidenceInProcess('ReceiptUnit', $receiptUnit->id, $path, $receiptUnit, $request['statusid']);
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseWithSuccess('saveEvidenceInProcessForList', 'Evidencia guardada con exito.');
        } else {
            return $this->getResponseWithErrors('saveEvidenceInProcessForList', 'Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }


    /**
     * @param $modeltype
     * @param $id
     * @param string|null $path
     * @param ReceiptUnit $receiptUnit
     * @param $photoid
     * @return void
     */
    public static function saveEvidenceInProcess($modeltype, $id, ?string $path, ReceiptUnit $receiptUnit, $photoid): void
    {
        EvidencePhoto::create([
            'centerid' => $receiptUnit->centerid,
            'warehouseid' => $receiptUnit->warehouseid,
            'model_type' => $modeltype,
            'model_id' => $id,
            'path' => $path,
            'createuser' => Auth::user()->id,
            'auxiliary01' => 'IP',
            'auxiliary02' => $receiptUnit->centerid,
            'auxiliary03' => $receiptUnit->warehouseid,
            'auxiliary04' => $photoid,
        ]);
    }

}
