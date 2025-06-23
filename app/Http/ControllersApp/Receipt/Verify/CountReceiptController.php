<?php

namespace App\Http\ControllersApp\Receipt\Verify;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\Verify\CountProcessRequest;
use App\Http\Services\ServiceReceiptContainer;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class CountReceiptController extends Controller
{
    use ResponseApi;


    public function saveInventoryCountReceipt(CountProcessRequest $request)
    {
        $receipt = $this->getReceiptAsn($request);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('saveInventoryCountReceipt',
                'El número de embarque no existe o se encuentra en estatus no permitido.');
        }
        $partnumber = $this->searchPartNumber($request['partnumberid']);
        if (empty($partnumber)) {
            return $this->getResponseWithErrors('saveInventoryCountReceipt', 'El número de parte no existe.');
        }
        $dias = $this->calculateExpireDate($request['productiondate'], $partnumber);
        $existStock = $this->searchPartNumberInStock($receipt, $partnumber, $request);
        if (empty($existStock)) {
            $this->registerNewPartNumberInReceiptStock($receipt, $partnumber, $request, $dias);
        } else {
            $existStock->quantitytrue = $existStock->quantitytrue + $request['quantity'];
            $existStock->save();
        }
        return $this->getResponseSuccess('saveInventoryCountReceipt', []);
    }

    public function getReceiptAsn($request)
    {
        return ReceiptAsn::controlAccessUser()
            ->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', $request['shipmentid'])
            ->first();
    }

    public function searchPartNumber($partnumberid)
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('prtnum', $partnumberid)->first();
    }

    public function searchPartNumberInStock(ReceiptAsn $receipt, InventoryPartnumber $partnumber, CountProcessRequest $request)
    {
        return ReceiptStock::where('receiptid', $receipt->id)
            ->where('partnumberid', '=', $partnumber->id)
            ->where('expirecode', '=', $request['expirecode'])
            ->where('productiondate', '=', $request['productiondate'])
            ->first();
    }

    /**
     * @param ReceiptAsn $receipt
     * @param InventoryPartnumber $partnumber
     * @param CountProcessRequest $request
     * @param string $dias
     * @return void
     */
    public function registerNewPartNumberInReceiptStock(ReceiptAsn $receipt, InventoryPartnumber $partnumber, CountProcessRequest $request, string $dias): void
    {
        ReceiptStock::create([
            'centerid' => $receipt->centerid,
            'warehouseid' => $receipt->warehouseid,
            'shipmentid' => $receipt->shipmentid,
            'receiptid' => $receipt->id,
            'partnumberid' => $partnumber->id,
            'category01id' => $partnumber->category01id,
            'category02id' => $partnumber->category02id,
            'stockcategoryid' => $receipt->stockcategoryid,
            'expirecode' => $request['expirecode'],
            'expiredate' => $dias,
            'productiondate' => $request['productiondate'],
            'quantitytrue' => $request['quantity']
        ]);
    }

    public function calculateExpireDate($productiondate, InventoryPartnumber $partnumber)
    {
        return Carbon::parse($productiondate)->addDays($partnumber->shelflife)->format('Y-m-d');
    }

}
