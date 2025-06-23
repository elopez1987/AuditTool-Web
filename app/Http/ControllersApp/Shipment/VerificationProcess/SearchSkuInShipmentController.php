<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchSkuRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentControl;
use App\Traits\ResponseApi;

class SearchSkuInShipmentController extends Controller
{
    use ResponseApi;

    public function index(SearchSkuRequest $request)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
        if (empty($orderDelivery)){
            return  $this->getResponseWithErrors('getSkuForShipmentCheck','El embarque no existe.');
        }
        $partNumber = InventoryPartnumber::controlAccessUser()
            ->where('prtnum', $request['partnumberid'])->first();
        if (empty($partNumber)) {
            return $this->getResponseWithErrors('getSkuForShipmentCheck','El numero de parte no existe.');
        }
        $shipmentControl = ShipmentControl::controlAccessUser()->where('orderid', $orderDelivery->id)
            ->where('partnumberid', $partNumber->id)->first();
        if (empty($shipmentControl)) {
            return $this->getResponseWithErrors('getSkuForShipmentCheck', 'Número de parte no solicitado en el embarque.');
        } else {
//            return $this->getResponseSuccess('getSkuForShipment', $partNumber);
            return $this->getResponseSuccess('getSkuForShipment', [
                'id' => $partNumber->id,
                'prtnum' => $partNumber->prtnum,
                'description' => $partNumber->lngdsc,
                'barcode' => $partNumber->barcode,
                'unitmeasure' => $partNumber->unitmeasure ? $partNumber->unitmeasure->description : '',
            ]);
        }
    }


}
