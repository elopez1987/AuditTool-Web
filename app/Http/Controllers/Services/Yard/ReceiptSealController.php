<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptUnit\SaveEvidenceForSealRequest;
use App\Http\Requests\Api\Yard\ReceiptUnit\SaveSealToReceiptuUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitSeal;
use App\Models\Yard\ReceiptUnitTicket;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class ReceiptSealController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;

    public const TYPE_MOV = 'SE';

    public function savesealtoreceiptunit(SaveSealToReceiptuUnitRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForBarcode($request['barcode']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('savesealtoreceiptunit', 'No existen registros de la unidad.');
        }
        DB::beginTransaction();
        try {
            $receiptunit->auxiliary05 = $request['advance'];
            $receiptunit->save();
            ReceiptUnitSeal::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'folionumber' => $receiptunit->id,
                'value01' => $request['typeid'],
                'seal01' => $request['seal01'],
                'seal02' => $request['seal02'],
                'seal03' => $request['seal03'],
            ]);
            ReceiptUnitTicket::updateOrCreate([
                'folionumber' => $receiptunit->id,
            ], [
                'responsibleid' => $request['responsibleid'],
                'areaid' => $request['areaid'],
                'contentid' => $request['contentid'],
            ]);
            DB::commit();
            return $this->getResponseWithSuccess('savesealtoreceiptunit', 'Sellos registrados exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return $this->getResponseWithErrors('savesealtoreceiptunit', 'Error al registrar los sellos.');
        }
    }

    public function saveEvidenceForSeal(SaveEvidenceForSealRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('saveEvidenceForSeal', 'No existe el folio ingresado.');
        }
        $this->createNewEvidence($request['file'], $receiptunit, self::TYPE_MOV, 'ReceiptUnitSeal', 'Yard', 'Seal', $request);
        return $this->getResponseSuccess('saveEvidenceForSeal', $receiptunit);
    }


}
