<?php

namespace App\Http\ControllersApp\Receipt\Incidence;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\ReceiptProcess\SaveIncidenceRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;


class RegisterIncidenceController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;

    public function saveRegisterIncidenceReceipt(SaveIncidenceRequest $request)
    {
        $receipt = $this->searchReceiptAsn($request['shipmentid']);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('saveRegisterIncidenceReceipt',
                'El número de embarque no existe o no se encuentra en estatus correcto.');
        }
        $this->createNewIncidence($receipt, $request);
        $this->createNewEvidence($request['file'], $receipt, 'INC', 'ReceiptAsn', 'Receipt', 'Incidence',$request);
        return $this->getResponseSuccess('saveRegisterIncidenceReceipt', []);
    }

    public function searchReceiptAsn($shipmentid)
    {
        return ReceiptAsn::controlAccessUser()->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', '=', $shipmentid)->first();
    }

    private function createNewIncidence($receipt, $request)
    {
        $partnumberid = $this->getPartNumberForTypeRequest($request);
        ReceiptIncidenceStock::create([
            'centerid' => $receipt->centerid,
            'warehouseid' => $receipt->warehouseid,
            'receiptid' => $receipt->id,
            'partnumberid' => $partnumberid,
            'quantity' => $request['quantity'],
            'categoryid' => $request['categoryincid'],
            'categorydetailid' => $request['categoryincdetailid'],
            'unitmeasureid' => $request['unitmeasureid'],
        ]);
    }

    private function getPartNumberForTypeRequest($request)
    {
        $inventoryPart = InventoryPartnumber::controlAccessUser()
            ->select(['id', 'prtnum', 'barcode'])
            ->where('prtnum', '=', $request['partnumberid'])
            ->orWhere('barcode', '=', $request['partnumberid'])
            ->first();
        $partnumberid = $inventoryPart->id;
        return $partnumberid;
    }

}
