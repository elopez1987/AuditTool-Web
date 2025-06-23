<?php

namespace App\Http\ControllersApp\Receipt\ReceiptProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\ReceiptProcess\SkuForReceiptRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Traits\ResponseApi;
use DB;

class SkuForReceiptController extends Controller
{
    use ResponseApi;

    public function getSkuForReceipt(SkuForReceiptRequest $request)
    {
        $partnumber = $this->searchPartNumber($request['partnumberid']);
        $receiptAsn = $this->searchShipment($request['shipmentid']);
        if (empty($receiptAsn)) {
            return $this->getResponseWithErrors('getSkuForReceipt', 'El número de recibo no se encuentra en proceso de descarga.');
        }
        $partnumbeInReceipt = $this->validateIfSkuExistInReceipt($receiptAsn, $partnumber);
        if (empty($partnumbeInReceipt)) {
            return $this->getResponseWithErrors('getSkuForReceipt', 'El número parte no pertenece al recibo.');
        }
        return $this->getResponseSuccess('getSkuForReceipt',
            [
                'partnumberid' => $partnumber->id,
                'description' => $partnumber->lngdsc,
                'barcode' => $partnumber->barcode
            ]);
    }

    private function searchPartNumber($partnumberid)
    {
        return DB::table('inventorypartnumbers')
            ->select(['id', 'lngdsc', 'barcode', 'prtnum'])
            ->where('prtnum', '=', $partnumberid)
            ->orWhere('barcode', '=', $partnumberid)
            ->where('statusid', 'E')->first();
    }

    private function searchShipment($shipmentid)
    {
        return ReceiptAsn::controlAccessUser()
            ->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', '=', $shipmentid)->first();
    }

    private function validateIfSkuExistInReceipt($receiptAsn, $partnumberid)
    {
        return ReceiptStock::controlAccessUser()->where('partnumberid', '=', $partnumberid->id)
            ->where('receiptid', $receiptAsn->id)->first();
    }


}
