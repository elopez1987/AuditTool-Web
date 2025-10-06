<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use PDF;

class EventReceiptController extends BaseController
{
    public function cancellationReceipt($id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $receipt->cancellationdate = now();
        $receipt->statusid = 'CE';
        $receipt->save();

        $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()->where('receiptid', $id)->first();
        if (! empty($schedulleReceipt)) {
            $schedulleReceipt->receiptid = null;
            $schedulleReceipt->save();
        }

        return $this->successResponse('receiptasn.index');
    }

    public function addpriority($id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $receipt->downloadpriority = 'S';
        $receipt->save();

        return $this->redirectBackInfo();
    }

    public function goodissuedate(Request $request, $id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $receipt->goodissuedate = now();
        $receipt->auxiliary02 = $request['gr'];
        $receipt->po = $request['gr'];
        $receipt->statusid = 'IG';
        $receipt->save();

        $folio = ReceiptUnit::whereId($receipt->folionumber)->first(['id', 'unitstatusid']);
        if ($folio !== null) {
            $folio->unitstatusid = '1';
            $folio->save();
        }

        return $this->redirectBackInfo();
    }

    public function kardexpdf($id)
    {
        $receipt = ReceiptAsn::controlAccessUser()->where('receiptasn.id', $id)
            ->leftJoin('systemstatus as se', 'receiptasn.auxiliary04', '=', 'se.statusid')
            ->leftJoin('users as u1', 'receiptasn.auxiliary08', '=', 'u1.username')
            ->leftJoin('users as u2', 'receiptasn.auxiliary09', '=', 'u2.username')
            ->leftJoin('users as u3', 'receiptasn.auxiliary10', '=', 'u3.username')
            ->select(['receiptasn.*', 'u1.name as analista', 'u2.name as monta', 'u3.name as verificador'])
            ->first();
        $checklistAsn = ReceiptAsnCheckList::where('receiptid', $id)->get();
        $images = EvidencePhoto::where('model_type', '=', 'ReceiptAsn')
            ->where('model_id', $id)->get();
        $incidences = ReceiptIncidenceStock::with(['partnumber', 'categoryincidence', 'subcategoryincidence'])
            ->where('receiptid', $id)->get();
        $stocks = $this->getTotalsReceipt($id);
        $containers = ReceiptContainer::getContainersForReceipt($id);
        $pdf = PDF::loadView('Receipt.ReceiptAsn.partials.kardex', compact('receipt', 'checklistAsn', 'images', 'stocks', 'incidences', 'containers'));

        return $pdf->download('Kardex de descarga - ' . $receipt->id . '.pdf');
    }

    public function getTotalsReceipt($receiptid)
    {
        return ReceiptStock::controlAccessUser()->select(
            'receiptstock.id',
            'inventorypartnumbers.prtnum',
            'inventorypartnumbers.lngdsc',
            'receiptstock.expirecode',
            'receiptstock.quantitytrue',
            DB::raw('(SUM(receiptcontainers.quantity) * inventorypartnumbers.untcas) as totalescaneado')
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
                'inventorypartnumbers.untcas'
            )
            ->get();
    }

    public function addNewCommentToReceipt(Request $request, $id)
    {
        $receiptAsn = ReceiptAsn::findOrFail($id);
        if ($request['comment'] !== '') {
            StorageComment::createCommentary('receiptasn', $receiptAsn->id, $request);
        }

        return $this->redirectBackSuccessMessage('Comentario agregado con exito.');
    }

    public function receiptasnclose($id)
    {
        $receiptasn = ReceiptAsn::findOrFail($id);
        $receiptasn->statusid = 'IG';
        $receiptasn->goodissuedate = Carbon::now();
        $receiptasn->save();

        return $this->redirectBackSuccessMessage('Descarga finalizada con exito.');
    }

    public function startprocess($id)
    {
        DB::beginTransaction();
        try {
            $receiptasn = ReceiptAsn::controlAccessUser()->findOrFail($id);
            if (empty($receiptasn)) {
                return $this->errorResponse('No se encontro el registro solicitado.');
            }
            $receiptunit = ReceiptUnit::controlAccessUser()->findOrFail($receiptasn->folionumber);
            if (empty($receiptunit)) {
                return $this->errorResponse('La unidad no cuenta con folio de patios.');
            }
            if ($receiptunit->zone->zoneid !== 'CO') {
                return $this->errorResponse('La unidad no se encuentra en Cortinas.');
            }

            $receiptasn->statusid = 'DE';
            $receiptasn->unloadstartdate = Carbon::now();
            $receiptasn->save();

            $receiptunit->startdate = Carbon::now();
            $receiptunit->statusid = 'EP';
            $receiptunit->save();

            StorageUnitTrace::createTraceUnit($receiptunit, '14');

            DB::commit();

            return $this->redirectBackSuccessMessage('Proceso de descarga iniciado con exito.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudo iniciar el proceso de descarga, favor de intentar nuevamente.');
        }
    }

    public function finishprocess($id)
    {
        DB::beginTransaction();
        try {
            $receiptasn = ReceiptAsn::controlAccessUser()->findOrFail($id);
            if (empty($receiptasn)) {
                return $this->errorResponse('No se encontro el registro solicitado.');
            }
            $receiptunit = ReceiptUnit::controlAccessUser()->findOrFail($receiptasn->folionumber);
            if (empty($receiptunit)) {
                return $this->errorResponse('La unidad no cuenta con folio de patios.');
            }
            if ($receiptunit->zone->zoneid !== 'CO') {
                return $this->errorResponse('La unidad no se encuentra en Cortinas.');
            }

            $receiptasn->statusid = 'TE';
            $receiptasn->unloadfinishdate = Carbon::now();
            $receiptasn->save();

            $receiptunit->finishdate = Carbon::now();
            $receiptunit->statusid = 'FI';
            $receiptunit->save();

            StorageUnitTrace::createTraceUnit($receiptunit, '15');

            DB::commit();

            return $this->redirectBackSuccessMessage('Proceso de descarga iniciado con exito.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudo iniciar el proceso de descarga, favor de intentar nuevamente.');
        }
    }
}
