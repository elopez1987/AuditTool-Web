<?php

namespace App\Http\Controllers\Receipt\SchedulleV2;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\StorageSchedulleReceipt;
use Auth;
use Illuminate\Http\Request;

class EventSchedulleController extends BaseController
{
    public function blockedeshedule(Request $request, $id)
    {

        $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
        if (empty($receiptId)) {
            return $this->errorResponse('No se encontró el registro solicitado.');
        }
        $receiptId->statusid = 'DPR-04';
        $receiptId->value01 = Auth::id();
        $receiptId->value02 = now();
        $receiptId->save();

        return $this->redirectBackSuccessMessage('Registro bloqueado con exito');
    }

    public function clearBlocked(Request $request, $id)
    {
        $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
        if (empty($receiptId)) {
            return $this->errorResponse('No se encontró el registro solicitado.');
        }
        $receiptId->statusid = 'DPR-01';
        $receiptId->value01 = null;
        $receiptId->save();

        return $this->redirectBackSuccessMessage('Registro desbloqueado con exito');
    }
}
