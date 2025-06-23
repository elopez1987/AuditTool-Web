<?php

namespace App\Http\ControllersApp\Receipt\CheckList;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\CheckList\SaveCheckListRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnCheckList;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaveCheckListReceiptController extends Controller
{
    use ResponseApi;

    public function saveCheckListForReceipt(SaveCheckListRequest $request)
    {
        $receipt = ReceiptAsn::controlAccessUser()
            ->where('shipmentid', '=', $request['shipmentid'])->first();
        if (!empty($receipt)) {
            $this->updateReceiptAsn($receipt, $request['type']);
            $this->destroyCheckListReceipt($receipt, $request);
            $this->createNewCheckListReceipt($request, $receipt);
            return $this->getResponseSuccess('saveCheckListForReceipt', []);
        }else{
            return  $this->getResponseWithErrors('saveCheckListForReceipt', 'Error al procesar la solicitud.');
        }
    }

    /**
     * @param $receipt
     * @param Request $request
     * @return void
     */
    public function destroyCheckListReceipt($receipt, Request $request): void
    {
        DB::table('receiptasnchecklist')
            ->where('receiptid', '=', $receipt->id)
            ->where('typeid', $request['type'])
            ->where('checklistid', $request['checklistid'])
            ->delete();
    }

    /**
     * @param Request $request
     * @param $receipt
     * @return void
     */
    public function createNewCheckListReceipt(Request $request, $receipt): void
    {
        $reg = $request->input('checklistdetailid');
        if ($reg != null) {
            $registros = count($reg) - 1;
            for ($i = 0; $i <= $registros; $i++)
                ReceiptAsnCheckList::create([
                'receiptid' => $receipt->id,
                'centerid' => $receipt->centerid,
                'checklistid' => $request['checklistid'],
                'typeid' => $request['type'],
                'checklistdetailid' => $request['checklistdetailid'][$i],
                'createuser' => Auth::user()->username,
                'created_at' => Carbon::now(),
            ]);
        }
    }

    public function updateReceiptAsn($receipt, $type)
    {
        if ($type == 'VF') {
            $receipt->auxiliary10 = Auth::user()->username;
        }
        if ($type == 'MT') {
            $receipt->auxiliary09 = Auth::user()->username;
        }
        $receipt->save();
    }


}
