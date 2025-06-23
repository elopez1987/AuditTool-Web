<?php

namespace App\Http\ControllersApp\Shipment\OrderDelivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\OrderDelivery\SearchShipmentInPlanRequest;
use App\Http\Resources\Shipments\OrderDeliveryResource;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;

class OrderDeliveriesController extends Controller
{
    use ResponseApi;

    public function index(SearchShipmentInPlanRequest $request)
    {
        $data = OrderDeliverie::controlAccessUser()->where('shipmentid',$request['shipmentid'])
        ->whereNotIn('statusid',['CA','DH'])->first();
        return $this->showOne('getDetailShipment',new OrderDeliveryResource($data));
    }


    
}
