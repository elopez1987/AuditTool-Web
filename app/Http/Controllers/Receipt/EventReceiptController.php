<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Models\Receipt\ReceiptAsnTime;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\StorageReceiptPallet;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use PDF;

class EventReceiptController extends BaseController
{
    public function cancellationReceipt(int $id)
    {
        DB::transaction(function () use ($id) {
            ReceiptAsn::where('id', $id)
                ->update([
                    'cancellationdate' => now(),
                    'statusid' => 'CE',
                ]);

            StorageSchedulleReceipt::controlAccessUser()
                ->where('receiptid', $id)
                ->update(['receiptid' => null]);
        });

        return $this->successResponse('receiptasn.index');
    }

    public function addpriority(int $id)
    {
        ReceiptAsn::where('id', $id)
            ->update(['downloadpriority' => 'S']);

        return $this->redirectBackInfo();
    }

    //	Cerrar descarga con registro de PO de SAP
    public function goodissuedate(Request $request, int $id)
    {
        $request->validate([
            'gr' => ['required'],
        ]);

        $receipt = ReceiptAsn::findOrFail($id);

        $parameterWarehouse = $this->getParametersOfWarehouse($receipt->warehouseid);

        if (! empty($parameterWarehouse) && in_array('SWH-45', $parameterWarehouse)) {
            $hasPallets = StorageReceiptPallet::where('receiptid', $id)->exists();

            if (! $hasPallets) {
                return $this->errorResponse('No se encontraron registros de tarimas en la descarga, favor de registrarlos para finalizar el proceso.');
            }
        }

        DB::transaction(function () use ($request, $receipt) {
            $receipt->update([
                'goodissuedate' => now(),
                'auxiliary02' => $request->input('gr'),
                'po' => $request->input('gr'),
                'statusid' => 'IG',
            ]);

            ReceiptUnit::where('id', $receipt->folionumber)
                ->whereNotNull('id')
                ->update(['unitstatusid' => '1']);
        });

        return $this->redirectBackInfo();
    }

    public function kardexpdf(int $id)
    {
        // Queries en paralelo — independientes entre sí
        [$pathImage, $receipt, $checklistAsn, $images, $incidences, $containers] = [
            SystemDetailParameter::where('parameterid', 'PARAM-IMG')->value('value01'),

            ReceiptAsn::controlAccessUser()
                ->where('receiptasn.id', $id)
                ->leftJoin('systemstatus as se', 'receiptasn.auxiliary04', '=', 'se.statusid')
                ->leftJoin('users as u1', 'receiptasn.auxiliary08', '=', 'u1.username')
                ->leftJoin('users as u2', 'receiptasn.auxiliary09', '=', 'u2.username')
                ->leftJoin('users as u3', 'receiptasn.auxiliary10', '=', 'u3.username')
                ->select([
                    'receiptasn.*',
                    'u1.name as analista',
                    'u2.name as monta',
                    'u3.name as verificador',
                ])
                ->firstOrFail(),

            ReceiptAsnCheckList::where('receiptid', $id)->get(),

            EvidencePhoto::where('model_type', 'ReceiptAsn')
                ->where('model_id', $id)
                ->get(),

            ReceiptIncidenceStock::with(['partnumber', 'categoryincidence', 'subcategoryincidence'])
                ->where('receiptid', $id)
                ->get(),

            ReceiptContainer::getContainersForReceipt($id),
        ];

        $stocks = $this->getTotalsReceipt($id);

        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [realpath(base_path()), realpath($pathImage)],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView('Receipt.ReceiptAsn.partials.kardex',
            compact('receipt', 'checklistAsn', 'images', 'stocks', 'incidences', 'containers')
        );

        return $pdf->download("Kardex de descarga - {$receipt->id}.pdf");
    }

    public function getTotalsReceipt($receiptid)
    {
        return ReceiptStock::controlAccessUser()->select(
            'receiptstock.id',
            'inventorypartnumbers.prtnum',
            'inventorypartnumbers.lngdsc',
            'receiptstock.expirecode',
            'receiptstock.quantitytrue',
            DB::raw('(SUM(receiptcontainers.quantity) * inventorypartnumbers.untcas) as totalescaneado'),
        )
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('receiptstock.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('receiptstock.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('receiptcontainers', function ($join) {
                $join->on('receiptstock.receiptid', '=', 'receiptcontainers.receiptid')
                    ->on('receiptstock.centerid', '=', 'receiptcontainers.centerid')
                    ->on('receiptstock.warehouseid', '=', 'receiptcontainers.warehouseid')
                    ->on('receiptstock.partnumberid', '=', 'receiptcontainers.partnumberid')
                    ->on('receiptstock.expirecode', '=', 'receiptcontainers.batch');
            })
            ->where('receiptstock.receiptid', '=', $receiptid)
            ->groupBy(
                'receiptstock.id',
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'receiptstock.expirecode',
                'receiptstock.quantitytrue',
                'inventorypartnumbers.untcas',
            )
            ->get();
    }

    public function addNewCommentToReceipt(Request $request, int $id)
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:500'],
        ]);

        StorageComment::createCommentary('receiptasn', $id, $request);

        return $this->redirectBackSuccessMessage('Comentario agregado con éxito.');
    }

    public function receiptasnclose(int $id)
    {
        ReceiptAsn::where('id', $id)
            ->update([
                'statusid' => 'IG',
                'goodissuedate' => now(),
            ]);

        return $this->redirectBackSuccessMessage('Descarga finalizada con éxito.');
    }

    public function startprocess(int $id)
    {
        try {
            $receiptAsn = ReceiptAsn::controlAccessUser()->findOrFail($id);

            $receiptUnit = ReceiptUnit::controlAccessUser()
                ->findOrFail($receiptAsn->folionumber);

            if ($receiptUnit->zone->zoneid !== 'CO') {
                return $this->errorResponse('La unidad no se encuentra en Cortinas.');
            }

            DB::transaction(function () use ($receiptAsn, $receiptUnit) {
                $receiptAsn->update([
                    'statusid' => 'DE',
                    'unloadstartdate' => now(),
                ]);

                $receiptUnit->update([
                    'startdate' => now(),
                    'statusid' => 'EP',
                ]);

                StorageUnitTrace::createTraceUnit($receiptUnit, '14');
            });

            return $this->redirectBackSuccessMessage('Proceso de descarga iniciado con éxito.');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('No se encontró el registro solicitado.');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->errorResponse('No se pudo iniciar el proceso de descarga, favor de intentar nuevamente.');
        }
    }

    public function finishprocess(int $id)
    {
        try {
            $receiptAsn = ReceiptAsn::controlAccessUser()->findOrFail($id);

            $receiptUnit = ReceiptUnit::controlAccessUser()
                ->findOrFail($receiptAsn->folionumber);

            if ($receiptUnit->zone->zoneid !== 'CO') {
                return $this->errorResponse('La unidad no se encuentra en Cortinas.');
            }

            DB::transaction(function () use ($receiptAsn, $receiptUnit) {
                $receiptAsn->update([
                    'statusid' => 'TE',
                    'unloadfinishdate' => now(),
                ]);

                $receiptUnit->update([
                    'finishdate' => now(),
                    'statusid' => 'FI',
                ]);

                StorageUnitTrace::createTraceUnit($receiptUnit, '15');
            });

            return $this->redirectBackSuccessMessage('Proceso de descarga finalizado con éxito.');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('No se encontró el registro solicitado.');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->errorResponse('No se pudo finalizar el proceso de descarga, favor de intentar nuevamente.');
        }
    }

    public function sendToBilling(int $id)
    {
        $receiptAsn = ReceiptAsn::findOrFail($id);

        ReceiptAsnTime::updateOrCreate(
            ['receiptid' => $receiptAsn->id],
            ['send_to_billing' => now()]
        );

        return $this->redirectBackSuccessMessage('Se envió a facturación con éxito.');
    }

    public function startBilling(int $id)
    {
        ReceiptAsnTime::where('receiptid', $id)
            ->update(['start_billing' => now()]);

        return $this->redirectBackSuccessMessage('Se inició facturación con éxito.');
    }

    public function finishBilling(int $id, Request $request)
    {
        $request->validate([
            'gr' => ['required', 'string'],
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                ReceiptAsnTime::where('receiptid', $id)
                    ->update(['finish_billing' => now()]);

                ReceiptAsn::where('id', $id)
                    ->update([
                        'goodissuedate' => now(),
                        'auxiliary02' => $request->input('gr'),
                        'po' => $request->input('gr'),
                        'statusid' => 'IG',
                    ]);
            });

            return $this->redirectBackSuccessMessage('Facturación finalizada con éxito.');

        } catch (Exception $e) {
            Log::error('Error en finishBilling', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('No se pudo registrar el fin de facturación, favor de intentar nuevamente.');
        }
    }
}
