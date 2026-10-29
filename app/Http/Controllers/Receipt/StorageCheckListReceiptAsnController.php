<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageCheckListReceiptAsnController extends BaseController
{
    public function index($id)
    {
        $receipt = ReceiptAsn::controlAccessUser()->where('uuid_asn', $id)->first();
        if (empty($receipt)) {
            return $this->errorResponse('No existe el número de recibo solicitado.');
        }

        $checklist = StorageCheckList::controlAccessUser()->where('type', '=', 'REC')
            ->where('statusid', '=', 'E')->first(['id', 'name']);
        if (empty($checklist)) {
            return $this->errorResponse('No existe check list de recibo configurado (REC).');
        }
        $details = StorageCheckListDetail::whereChecklistid($checklist->id)
            ->where('auxiliary01', '=', 'AR')
            ->select(['id', 'description', 'checklistid', 'auxiliary01'])
            ->where('statusid', '=', 'E')
            ->get();

        $actives = ReceiptAsnCheckList::where('receiptid', $receipt->id)
            ->where('typeid', 'AR')->where('checklistid', $checklist->id)
            ->select('checklistdetailid')->get();

        return view(
            'Receipt.ScheduleReceipt.partials.checklist',
            compact('receipt', 'details', 'checklist', 'actives'),
        );
    }

    public function addchecklistAR($id, Request $request)
    {
        $receipt = ReceiptAsn::where('id', $id)->first();

        $this->destroyCheckListOfReceipt($receipt, $request);
        $this->createCheckListReceipt($request, $receipt);

        return $this->redirectBackSuccess();
    }

    private function destroyCheckListOfReceipt($receipt, $request): void
    {
        DB::table('receiptasnchecklist')
            ->where('receiptid', '=', $receipt->id)
            ->where('typeid', $request['typeid'])
            ->where('checklistid', $request['checklistid'])
            ->delete();
    }

    private function createCheckListReceipt(Request $request, $receipt): void
    {
        $detailIds = $request->input('checklistdetailid');

        if (empty($detailIds)) {
            return;
        }

        $now = now();
        $username = Auth::user()->username;

        $base = [
            'receiptid' => $receipt->id,
            'checklistid' => $request->input('checklistid'),
            'typeid' => $request->input('typeid'),
            'createuser' => $username,
            'created_at' => $now,
        ];

        $rows = array_map(
            fn ($detailId) => $base + ['checklistdetailid' => $detailId],
            array_values($detailIds)
        );

        DB::transaction(function () use ($rows) {
            foreach (array_chunk($rows, 200) as $chunk) {
                ReceiptAsnCheckList::insert($chunk);
            }
        });
    }
}
