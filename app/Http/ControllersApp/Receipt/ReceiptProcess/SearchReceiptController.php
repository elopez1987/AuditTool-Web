<?php

namespace App\Http\ControllersApp\Receipt\ReceiptProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\ReceiptProcess\SearchReceiptRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Traits\ResponseApi;

class SearchReceiptController extends Controller
{
   use ResponseApi;
    public function getReceiptInProcess(SearchReceiptRequest $request)
    {
        $receipt = $this->getReceipt($request);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('getReceiptInProcess',
                'El número de embarque no existe o no se encuentra en estatus correcto.');
        }
        return $this->getResponseSuccess('getReceiptInProcess', $receipt);
    }

    public function getReceipt($request)
    {
        return ReceiptAsn::controlAccessUser()
            ->join('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->join('shipmentcarriers', 'receiptasn.carrierid', '=', 'shipmentcarriers.id')
            ->whereIn('receiptasn.statusid', ['DE', 'TE', 'ER'])
            ->where('receiptasn.shipmentid', '=', $request['shipmentid'])
            ->orWhere('receiptasn.billoflading', '=', $request['shipmentid'])
            ->select(['receiptasn.shipmentid', 'receiptproviders.name as provider',
                'shipmentcarriers.name as carrier','receiptasn.verifystartdate','receiptasn.verifyfinishdate',
            ])->first();
    }
}
