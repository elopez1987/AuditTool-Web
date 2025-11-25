<?php

namespace App\Http\Controllers\Receipt\SchedulleV2;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\StorageSchedulleReceipt;
use Auth;
use Illuminate\Http\Request;

class EventSchedulleController extends BaseController
{
    public function blockedeshedule(Request $request, $id)
    {
        try {
            $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
            if (empty($receiptId)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró el registro solicitado.',
                    ], 404);
                }

                return $this->errorResponse('No se encontró el registro solicitado.');
            }

            $receiptId->statusid = 'DPR-04';
            $receiptId->value01 = Auth::id();
            $receiptId->value02 = now();
            $receiptId->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro bloqueado con éxito',
                ]);
            }

            return $this->redirectBackSuccessMessage('Registro bloqueado con exito');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al bloquear el registro: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse($e->getMessage());
        }
    }

    public function clearBlocked(Request $request, $id)
    {
        try {
            $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
            if (empty($receiptId)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró el registro solicitado.',
                    ], 404);
                }

                return $this->errorResponse('No se encontró el registro solicitado.');
            }

            $receiptId->statusid = 'DPR-01';
            $receiptId->value01 = null;
            $receiptId->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro desbloqueado con éxito',
                ]);
            }

            return $this->redirectBackSuccessMessage('Registro desbloqueado con exito');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al desbloquear el registro: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse($e->getMessage());
        }
    }

    public function addpriorityreceipt(Request $request, $id)
    {
        try {
            $receipt = ReceiptAsn::findOrFail($id);
            $receipt->downloadpriority = 'S';
            $receipt->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prioridad asignada con éxito',
                    'receipt_id' => $receipt->id,
                ]);
            }
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo asignar la prioridad: ' . $e->getMessage(),
                ], 500);
            }
        }
    }
}
