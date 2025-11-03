<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\RefuseUnit\RegisterRefuseUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForReceipt;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForShipment;
use DB;
use Exception;

class RefuseUnitController extends BaseController
{
    use DeclineUnitForReceipt;
    use DeclineUnitForShipment;
    use GetParameters;

    public function index()
    {
        $this->authorize('refuseunit');

        return view('Yards.RefuseUnit.index');
    }

    public function store(RegisterRefuseUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $folio = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($folio)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de folio no existe!!',
                    ], 422);
                }
            }
            $receiptunit = ReceiptUnit::updateUnitForRefuse($folio->id);
            if ($receiptunit->razonstatusid === 1) {
                $this->declineUnitForShipment($receiptunit);
            }
            if ($receiptunit->razonstatusid === 2) {
                $this->declineUnitForReceipt($receiptunit->id);
            }
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad rechazada con éxito!!',
                ], 200);
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la solicitud, intente más tarde: ' . $error,
                ], 500);
            }
        }
    }
}
