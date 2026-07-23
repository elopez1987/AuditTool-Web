<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\RefuseUnit\RegisterRefuseUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitStatus;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForReceipt;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForShipment;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;

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

    public function store(RegisterRefuseUnitRequest $request): JsonResponse
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);

        if (empty($receiptunit)) {
            return response()->json([
                'success' => false,
                'message' => 'El número de folio no existe.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            match ((int) $receiptunit->razonstatusid) {
                1 => $this->declineUnitForShipment($receiptunit),
                2 => $this->declineUnitForReceipt($receiptunit->id),
                default => null,
            };

            $this->updateUnitForRefuse($receiptunit);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unidad rechazada con éxito.',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function updateUnitForRefuse(ReceiptUnit $unit): ReceiptUnit
    {
        $unitStatus = StorageUnitStatus::where('unitstatusid', 'RE')
            ->select(['id', 'name'])
            ->first();
        $unit->update([
            'autorizaoutputdate' => now(),
            'statusid' => 'OU',
            'shipmentid' => null,
            'unitstatusid' => $unitStatus->id,
        ]);

        StorageUnitTrace::createTraceUnit($unit, '23');

        return $unit;
    }
}
