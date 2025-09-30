<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Evidences;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\CheckListEvidenceProcessUnit;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageFolioClient;
use App\Traits\Web\General\StorageWebPhotoTrait;
use Auth;
use Illuminate\Http\Request;

class ReceiptUnitEvidenceStockController extends BaseController
{
    use StorageWebPhotoTrait;

    //    --Módulo de subida de evidencia de proceso de carga o desdecarga desde la existencia de caja, puede ser parametrizado para usar
    //     un check list de fotos que son requeridas, si el parametro APP-02 se encuentra activo entonces funcionará
    //    con el check list parametrizado.
    //    IP --Evidencia en proceso

    //    Subida de evidencia en proceso de carga o desdecarga desde la version web en existencia
    public function index($id)
    {
        $receiptunit = ReceiptUnit::searchForId($id);
        $parameterCenter = $this->getParametersApp($receiptunit->centerid);
        $folionumberType = StorageFolioClient::controlAccessUser()->where('folionumber', $receiptunit->id)->first();
        if (empty($folionumberType)) {
            return $this->errorResponse('No existe folio de cliente favor de validar el concepto de entrada.');
        }
        if ($folionumberType->auxiliary01 === 'TDIRECTO') {
            $receiptComments = [
                1 => 'SH',
                2 => 'RE',
                17 => 'DE',
            ];
            $comment = $receiptComments[$receiptunit->razonstatusid] ?? null;
            $listCheckList = SystemStatus::select(['statusid', 'name'])
                ->where('schemaid', 'FIP')
                ->where('comment', $comment)
                ->where('auxiliary02', $receiptunit->centerid)
                ->whereNotIn('statusid', function ($query) use ($receiptunit) {
                    $query->select('auxiliary04')
                        ->from((new EvidencePhoto())->getTable())
                        ->where('model_id', $receiptunit->id)
                        ->where('model_type', 'ReceiptUnit')
                        ->where('auxiliary01', 'IP');
                })->get();
        } else {
            $listCheckList = CheckListEvidenceProcessUnit::where('modelid', $receiptunit->id)
                ->where('modeltype', '=', 'ReceiptUnits')
                ->select(['id', 'description as name', 'statusid'])
                ->whereNotIn('statusid', ['D'])
                ->get();
        }
        return view(
            'Yards.ReceiptUnits.EvidenceProcess.index',
            compact('receiptunit', 'listCheckList', 'folionumberType', 'parameterCenter')
        );
    }

    public function saveTypeShipmentEvidence(Request $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folioid']);
        $folionumberType = StorageFolioClient::where('folionumber', $receiptunit->id)->first();
        $folionumberType->auxiliary01 = $request['typeshipment'];
        $folionumberType->save();
        if ($request['typeshipment'] === 'TIROS') {
            $this->createNewCheckList($receiptunit, $request);
        }
        return $this->redirectBackSuccessMessage('Registro creado con exito.');
    }

    public function saveEvidenceProcessForCheckList(Request $request)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $request['folioid'])->first();
        $path = $this->saveFileToModel($request['file'], $receiptunit->centerid, 'Yard', 'ReceiptUnit', 'InProcess');

        if ($request['typeshipment'] === 'TIROS') {
            $checklist = CheckListEvidenceProcessUnit::where('id', $request['id'])->first();
            $checklist->path = $path;
            $checklist->value01 = Auth::user()->username;
            $checklist->statusid = 'D';
            $checklist->save();
        }
        if ($request['typeshipment'] === 'TDIRECTO') {
            $this->createEvidencePhoto('ReceiptUnit', $receiptunit->id, $path, 'IP', $receiptunit);
        }
        return $this->redirectBackSuccessMessage('Evidencia cargada con exito.');
    }

    public function createNewCheckList($folionumber, $request)
    {
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->id,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Caja vacía',
        ]);

        for ($i = 1; $i <= $request['ntiros']; $i++) {
            CheckListEvidenceProcessUnit::create([
                'centerid' => $folionumber->centerid,
                'modelid' => $folionumber->id,
                'modeltype' => 'ReceiptUnits',
                'description' => 'Tiro_' . $i,
            ]);
        }
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->id,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Fin de carga',
        ]);
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->id,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Sellos',
        ]);
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->id,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Caja cerrada',
        ]);
    }
}
