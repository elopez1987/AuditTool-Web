<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStage;
use App\Models\Yard\StorageLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssignStageController extends BaseController
{
    public function index($id)
    {
        $this->authorize('receiptasn.addstage');
        $receiptasn = ReceiptAsn::where('id', $id)->first();
        $locations = StorageLocation::controlAccessUser()
            ->leftJoin('storagezones', 'storagelocations.zoneid', 'storagezones.id')
            ->whereIn('storagezones.zoneid', ['CO'])
            ->whereNotIn('storagelocations.id', function ($query) use ($id) {
                $query->select('locationid')
                    ->from('receiptstage')
                    ->where('receiptid', $id);
            })
            ->select(['storagelocations.id', 'storagelocations.zoneid',
                'storagelocations.locationid', 'storagelocations.name',
            ])
            ->get();

        $stages = ReceiptStage::where('receiptid', $id)->get();

        return view(
            'Receipt.ScheduleReceipt.addstage',
            compact('receiptasn', 'locations', 'stages')
        );
    }

    public function receiptasnassingstage($id, Request $request)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $locationstageArray = $request->input('locationstage');
        foreach ($locationstageArray as $locationstage) {
            ReceiptStage::create([
                'centerid' => $receipt->centerid,
                'warehouseid' => $receipt->warehouseid,
                'receiptid' => $receipt->id,
                'locationid' => $locationstage,
            ]);
        }

        return $this->redirectBackSuccessMessage('Cortinas asignadas con exito.');
    }

    public function deletestagereceipt(Request $request)
    {
        $stage = ReceiptStage::where('id', $request['id'])->first();
        $stage->delete();

        return $this->responseSuccessAjax('', Carbon::now());
    }
}
