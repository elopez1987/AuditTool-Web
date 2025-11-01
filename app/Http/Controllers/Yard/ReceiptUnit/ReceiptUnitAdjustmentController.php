<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitStatus;
use Illuminate\Http\Request;

class ReceiptUnitAdjustmentController extends BaseController
{
    //   Módulo de ajuste de unidad
    public function index()
    {
        $this->authorize('receiptunits.adjustmentunit');
        $status = $this->getStatusForSchema('RUS');
        $concepts = StorageReason::where('type', 'IN')->select(['id', 'name'])->get();
        $unitstatus = StorageUnitStatus::where('statusid', 'E')->select(['id', 'name'])->get();

        return view('Yards.AdjustmentUnit.index', compact('status', 'concepts', 'unitstatus'));
    }

    public function adjustmentunitstore(Request $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe número de folio, favor de validar.',
                ], 422);
            }
        }

        $receiptunit->operatorname = $request['operatorname'];
        $receiptunit->boxnumber = $request['boxnumber'];
        $receiptunit->econumber = $request['econumber'];
        $receiptunit->trucknumber = $request['trucknumber'];
        $receiptunit->foliotruck = $request['foliotruck'];
        $receiptunit->shipmentid = $request['shipmentid'];
        $receiptunit->statusid = $request['statusid'] ?? $receiptunit->statusid;
        $receiptunit->razonstatusid = $request['razonstatusid'] ?? $receiptunit->razonstatusid;
        $receiptunit->unitstatusid = $request['unitstatus'] ?? $receiptunit->unitstatusid;
        $receiptunit->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado con éxito.',
            ], 200);
        }
    }
}
