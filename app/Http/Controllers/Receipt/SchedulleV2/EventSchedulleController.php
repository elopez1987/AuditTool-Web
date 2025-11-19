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
            // Respuesta para peticiones AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el registro solicitado.',
                ], 404);
            }
        }

        $receiptId->statusid = 'DPR-04';
        $receiptId->value01 = Auth::id();
        $receiptId->value02 = now();
        $receiptId->save();

        // Respuesta para peticiones AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro bloqueado con éxito',
            ]);
        }

    }

    public function clearBlocked(Request $request, $id)
    {
        $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
        if (empty($receiptId)) {
            // Respuesta para peticiones AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el registro solicitado.',
                ], 404);
            }
        }

        $receiptId->statusid = 'DPR-01';
        $receiptId->value01 = null;
        $receiptId->save();

        // Respuesta para peticiones AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro desbloqueado con éxito',
            ]);
        }

    }
}
