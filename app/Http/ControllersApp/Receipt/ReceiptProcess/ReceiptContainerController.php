<?php

namespace App\Http\ControllersApp\Receipt\ReceiptProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\ReceiptProcess\SaveContainerRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptStock;
use App\Traits\ResponseApi;

class ReceiptContainerController extends Controller
{
    use ResponseApi;

    public function saveReceiptContainerInProceses(SaveContainerRequest $request)
    {
        $receiptAsn = $this->getReceiptAsn($request['shipmentid']);
        $partnumber = $this->getPartnumber($request['partnumberid']);
        if (empty($partnumber)) {
            return $this->getResponseWithErrors('saveReceiptContainerInProceses',
                'El número de parte no existe, favor de validar.');
        }
        $partnumberStock = $this->getPartNumberStock($receiptAsn, $partnumber);
        if (empty($partnumberStock)) {
            return $this->getResponseWithErrors('saveReceiptContainerInProceses',
                'El número de parte no es solicitado en el recibo,favor de agregarlo.');
        }
        if ($request['quantity'] > $partnumber->untpal) {
            return $this->getResponseWithErrors('saveReceiptContainerInProceses',
                'La cantidad no puede ser mayor al estandar del producto.');
        }
        $lpnExist = $this->searchLpnInStock($request['lpnid']);
        if (!empty($lpnExist)) {
            return $this->getResponseWithErrors('saveReceiptContainerInProceses',
                'El número de lpn ya se encuentra registrado en sistema.');
        }
        $this->createNewContainerInReceipt($receiptAsn, $request, $partnumber);
        return $this->getResponseSuccess('saveReceiptContainerInProceses', []);
    }


    private function getReceiptAsn($shipmentid)
    {
        return ReceiptAsn::controlAccessUser()
            ->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', $shipmentid)
            ->first();
    }

    private function getPartnumber($partnumberid)
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('prtnum', '=', $partnumberid)
            ->select(['id', 'prtnum', 'lngdsc', 'untpal'])->first();
    }


    public function getPartNumberStock(ReceiptAsn $receiptAsn, InventoryPartnumber $partnumber)
    {
        return ReceiptStock::controlAccessUser()
            ->where('receiptid', $receiptAsn->id)
            ->where('partnumberid', '=', $partnumber->id)->first();
    }


    public function searchLpnInStock($lpnid)
    {
        return ReceiptContainer::where('lpnid', '=', $lpnid)->first();
    }

    private function createNewContainerInReceipt($receiptAsn, $request, $partnumber)
    {
        ReceiptContainer::create([
            'centerid' => $receiptAsn->centerid,
            'warehouseid' => $receiptAsn->warehouseid,
            'receiptid' => $receiptAsn->id,
            'partnumberid' => $partnumber->id,
            'lpnid' => $request['lpnid'],
            'quantity' => $request['quantity'],
            'batch' => $request['batch'],
            'productiondate' => $request['productiondate'],
        ]);
    }
}
