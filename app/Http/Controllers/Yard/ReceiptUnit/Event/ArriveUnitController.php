<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\SaveArriveUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use DB;
use Exception;

class ArriveUnitController extends BaseController
{
    //    Registro de llegada a mesa de control
    public function index()
    {
        $this->authorize('viewmovementsofbox');

        return view('Yards.RegisterMovementsBox.index');
    }

    public function saveArriveUnitToCenter(SaveArriveUnitRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return response()->json([
                'success' => false,
                'message' => 'El número de caja no se encuentra en patio!!',
            ], 400);
        }
        DB::beginTransaction();
        try {

            if ($receiptunit->statusid !== 'QI') {
                return response()->json([
                    'success' => false,
                    'message' => 'La unidad se encuentra en estatus diferente ENTRADA AUTORIZADA.',
                ], 400);
            }
            if ($receiptunit->deliverdate !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'La unidad ya cuenta con registro de Mesa de Control',
                ], 400);
            }
            $this->updateDataUnit($receiptunit);
            StorageUnitTrace::createTraceUnit($receiptunit, '10');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Datos actualizados correctamente!!',
            ], 200);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function updateDataUnit(ReceiptUnit $receiptunit)
    {
        $receiptunit->deliverdate = now();
        $receiptunit->statusid = 'IN';
        $receiptunit->save();
    }
}
