<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class KeyReceptionController extends BaseController
{
    public function index()
    {
        return view('Yards.KeyReception.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folionumber' => 'required|min:4|max:20',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('El número de folio es obligatorio.');
        }
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $request['folionumber'])->first();
        if (empty($receiptunit)) {
            return $this->errorResponse('No existen registros del folio  ingresado.');
        }
        if ($receiptunit->statusid == 'TR') {
            return $this->errorResponse('La unidad se encuentra terminado, favor de validar.');
        }
        if ($receiptunit->keydate != null) {
            return $this->errorResponse('La unidad ya cuenta con registro de ingreso de llaves.');
        }
        if ($receiptunit->ensnare == null) {
            return $this->errorResponse('La unidad no esta enrampada, favor de validar.');
        }
        try {
            $receiptunit->keydate = Carbon::now();
            $receiptunit->save();
            StorageUnitTrace::createTraceUnit($receiptunit, '28');

            return $this->redirectBackSuccessMessage('Se registro la fecha de ingreso de llaves.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->errorResponse('Error al procesar el movimiento');
        }
    }
}
