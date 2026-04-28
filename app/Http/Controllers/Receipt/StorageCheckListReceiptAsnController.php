<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageCheckListReceiptAsnController extends BaseController
{
    public function index($id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $checklist = StorageCheckList::controlAccessUser()->where('type', '=', 'REC')
            ->where('statusid', '=', 'E')->first(['id', 'name']);
        if (empty($checklist)) {
            return $this->errorResponse('No existe check list de recibo configurado (REC).');
        }
        $details = StorageCheckListDetail::controlAccessUser()->whereChecklistid($checklist->id)
            ->where('auxiliary01', '=', 'AR')
            ->select(['id', 'description', 'checklistid', 'auxiliary01'])
            ->where('statusid', '=', 'E')
            ->get();

        $categories = SystemStatus::where('schemaid', 'CRE')
            ->where('centerid', $receipt->centerid)
            ->where('warehouseid', $receipt->warehouseid)
            ->select(['name', 'statusid'])->get();

        if ($categories->isEmpty()) {
            return $this->errorResponse('No existe categoria de material en el esquema CRE.');
        }
        $actives = ReceiptAsnCheckList::where('receiptid', $id)
            ->where('typeid', 'AR')->where('checklistid', $checklist->id)
            ->select('checklistdetailid')->get();

        return view(
            'Receipt.ScheduleReceipt.partials.checklist',
            compact('receipt', 'details', 'checklist', 'categories', 'actives')
        );
    }

    public function addchecklistAR($id, Request $request)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $receipt->auxiliary03 = $request['categoryid']; // Categoria de material esquema CRE
        $receipt->auxiliary08 = Auth::user()->username;
        $receipt->save();
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
        $reg = $request->input('checklistdetailid');
        if ($reg !== null) {
            $registros = count($reg) - 1;
            for ($i = 0; $i <= $registros; $i++) {
                ReceiptAsnCheckList::create([
                    'receiptid' => $receipt->id,
                    'centerid' => $receipt->centerid,
                    'checklistid' => $request['checklistid'],
                    'typeid' => $request['typeid'],
                    'checklistdetailid' => $request['checklistdetailid'][$i],
                    'createuser' => Auth::user()->username,
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }
}
