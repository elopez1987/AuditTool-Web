<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchStatusPickRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;

class SearchStatusPickingController extends Controller
{
    use ResponseApi;
    public function index(SearchStatusPickRequest $request)
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', '=', $request['shipmentid'])
            ->leftJoin('shipmentdataoperation','orderdeliveries.id','=','shipmentdataoperation.orderid')
            ->select(['orderdeliveries.shipmentid','shipmentdataoperation.fullpicking'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getStatusPickForShipment','El número de embarque no existe, favor de validar.');
        }
        return $this->getResponseSuccess('getStatusPickForShipment', $shipment);
    }
}
