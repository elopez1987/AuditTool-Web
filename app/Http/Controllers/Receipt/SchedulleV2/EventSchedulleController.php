<?php

namespace App\Http\Controllers\Receipt\SchedulleV2;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventSchedulleController extends BaseController
{
    //	Bloquear secuencia de horario de descarga v2
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

    //	Limpiar bloqueo de descarga
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

    //	Agregar prioridad a la descarga desde el horario v2
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

    //	Cancelar descarga desde el horario v2
    public function cancelReceipt(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $receiptId = StorageSchedulleReceipt::controlAccessUser()->where('id', $id)->first();
            if (empty($receiptId)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró el registro solicitado.',
                    ], 404);
                }
            }

            $receipt = ReceiptAsn::controlAccessUser()->where('id', $receiptId->receiptid)->first();
            if ($receipt) {
                $receipt->update([
                    'statusid' => 'CE',
                    'cancellationdate' => now(), ]);
            }

            StorageShipmentTransport::controlAccessUser()
                ->where('orderid', $receiptId->receiptid)
                ->where('auxiliary04', 'ReceiptAsn')
                ->delete();

            $receiptId->update([
                'receiptid' => null,
                'statusid' => 'DPR-01',
            ]);

            DB::commit();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Descarga cancelada con éxito.',
                    'receipt_id' => $receipt->id,
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cancelar la descarga: ',
                ], 500);
            }
        }
    }

    //	Reprogramar descarga desde el horario v2
    public function movetosecuente(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()
                ->where('id', $id)->first();

            $secuenceNew = StorageSchedulleReceipt::controlAccessUser()
                ->where('id', $request->secuence)->first();
            if ($secuenceNew) {

                $hour = SystemStatus::where('statusid', $secuenceNew->secuence)->first();
                $workday = Carbon::parse($request->workday);
                $date = $hour->auxiliary05 === 'S' ? $workday->addDay() : $workday;
                $combinedDateTime = $date->toDateString() . ' ' . $hour->auxiliary04;
                $formattedDateTime = Carbon::createFromFormat('Y-m-d H:i', $combinedDateTime);

                $receiptAsn = ReceiptAsn::controlAccessUser()
                    ->where('id', $schedulleReceipt->receiptid)->first();

                if ($receiptAsn) {
                    $receiptAsn->update([
                        'addasndate' => $formattedDateTime,
                    ]);
                }

                $secuenceNew->update([
                    'receiptid' => $schedulleReceipt->receiptid,
                    'value01' => Auth::id(), // Usuario que agrega el recibo al plan de entrega
                    'value02' => now(), // Fecha y hora en que se agrega el recibo al plan de entrega
                    'value03' => $formattedDateTime, // Fecha y hora de la cita programada para el recibo
                    'statusid' => 'DPR-03',
                ]);

            }

            $schedulleReceipt->update(
                [
                    'receiptid' => null,
                    'value01' => null,
                    'statusid' => 'DPR-01',
                ],
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registro reprogramado correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al reprogramar el registro.',
            ]);
        }

    }
}
