<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EventReceiptController extends BaseController
{
    public function cancellationReceipt(int $id)
    {
        DB::transaction(function () use ($id) {
            ReceiptAsn::where('id', $id)
                ->update([
                    'cancellationdate' => now(),
                    'statusid' => 'CA',
                ]);
        });

        return $this->successResponse('receiptasn.index');
    }

    public function goodissuedate(Request $request, int $id)
    {
        $receipt = ReceiptAsn::findOrFail($id);

        DB::transaction(function () use ($receipt) {
            $receipt->update([
                'goodissuedate' => now(),
                'statusid' => 'TR',
            ]);
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
                'statusid' => 'TR',
                'goodissuedate' => now(),
            ]);

        return $this->redirectBackSuccessMessage('Descarga finalizada con éxito.');
    }
}
