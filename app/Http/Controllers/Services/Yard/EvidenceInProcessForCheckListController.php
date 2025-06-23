<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Yard\CheckListEvidenceProcessUnit;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageFolioClient;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\ResponseApi;
use App\Traits\Yard\CreateCheckListEvidenceProcess;
use DB;
use Illuminate\Http\Request;


class EvidenceInProcessForCheckListController extends Controller
{
    use ResponseApi;
    use CreateCheckListEvidenceProcess;
    use SavePhotoInRegister;

//    Registra el tipo y crear los puntos a validar
    public function saveTypeShipmentLoad(Request $request)
    {
        if ($request['folioid'] == null) {
            return $this->getResponseWithErrors('saveTypeShipmentLoad', 'Ingrese un número de folio.');
        }
        DB::beginTransaction();
        try {
            StorageFolioClient::updateOrCreate([
                'folionumber' => $request['folioid'],
            ], [
                'auxiliary01' => $request['typeshipment'],
            ]);
            if ($request['typeshipment'] == 'TIROS') {
                $folionumber = ReceiptUnit::controlAccessUser()->where('id', $request['folioid'])
                    ->whereNotIn('statusid', ['TR'])->select(['id as folionumber', 'centerid'])->first();
                $this->createNewCheckList($folionumber, $request);
            }
            DB::commit();
            $success = true;
        } catch (\Exception) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('saveTypeShipmentLoad', 'Registro creado con exito.');
        }else{
            return $this->getResponseWithErrors('saveTypeShipmentLoad', 'Error al procesar la solicitud, intente en un momento.');
        }
    }

//    Consulta la lista de puntos a validar durante el proceso
    public function getCheckListProcesForOrder(Request $request)
    {
        if ($request['folioid'] == null) {
            return $this->getResponseWithErrors('getCheckListProcesForOrder', 'Ingrese un número de folio.');
        }
        $data = CheckListEvidenceProcessUnit::where('modelid', $request['folioid'])
            ->where('modeltype', '=', 'ReceiptUnits')
            ->select(['id', 'description', 'statusid'])
            ->whereNotIn('statusid', ['D'])
            ->get();
        if ($data == null) {
            return $this->getResponseWithErrors('getCheckListProcesForOrder', 'El número de folio no cuenta con check list creado.');
        }
        return $this->getResponseSuccess('getCheckListProcesForOrder', $data);
    }


    public function saveEvidenceInProcessForCheckList(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request['file'];
            $folionumber = $request['folionumber'];
            $checklistid = $request['$checklistid'];
            if ($file === '' || $folionumber === '' || $checklistid === '') {
                return $this->getResponseWithErrors('saveEvidenceInProcessForCheckList', 'No se ha adjuntado ningún archivo.');
            }
            $receiptUnit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptUnit)) {
                return $this->getResponseWithErrors('saveEvidenceInProcessForCheckList', 'No existe el número de folio buscado.');
            }
            $checklist = CheckListEvidenceProcessUnit::where('id', $request['checklistid'])->first();
            $path = $this->createNewEvidenceForRegister($file, $receiptUnit, 'Yard', 'ReceiptUnit', 'InProcess');
            $checklist->path = $path;
            $checklist->value01 = \Auth::user()->username;
            $checklist->statusid = 'D';
            $checklist->save();
            DB::commit();
            return $this->getResponseWithSuccess('saveEvidenceInProcessForCheckList', 'Evidencia guardada con exito.');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->getResponseWithErrors('saveEvidenceInProcessForCheckList', 'Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }


}

