<?php

namespace App\Http\ControllersApp\Receipt\Verify;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\Verify\VerifyProccessRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class VerifyProccessController extends Controller
{
    use ResponseApi;

    public function saveStartVerifyReceipt(VerifyProccessRequest $request)
    {
        $receipt = $this->getReceiptAsn($request['shipmentid']);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('saveStartVerifyReceipt', 'El embarque ya no se encuentra en el estatus correcto.');
        }
        if ($receipt->verifystartdate == null) {
            $receipt->verifystartdate = Carbon::now();
            $receipt->save();
            return $this->getResponseSuccess('saveStartVerifyReceipt', []);
        } else {
            return $this->getResponseWithErrors('saveStartVerifyReceipt', 'El proceso ya fue iniciado.');
        }
    }

    public function saveFinishVerifyReceipt(VerifyProccessRequest $request)
    {
        $receipt = $this->getReceiptAsn($request['shipmentid']);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('saveStartVerifyReceipt', 'El embarque ya no se encuentra en el estatus correcto.');
        }
        if ($receipt->verifyfinishdate == null) {
            $receipt->verifyfinishdate = Carbon::now();
            $receipt->save();
            return $this->getResponseSuccess('saveStartVerifyReceipt', []);
        } else {
            return $this->getResponseWithErrors('saveFinishVerifyReceipt', 'El proceso ya fue registrado.');
        }
    }

    private function getReceiptAsn($shipmentid)
    {
        return ReceiptAsn::controlAccessUser()
            ->select(['id', 'verifystartdate', 'verifyfinishdate'])
            ->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', $shipmentid)
            ->first();
    }

}
