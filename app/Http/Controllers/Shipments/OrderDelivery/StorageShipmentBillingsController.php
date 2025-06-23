<?php

namespace App\Http\Controllers\Shipments\OrderDelivery;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\OrderDelivery\CreateDeliveryRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageShipmentBilling;

class StorageShipmentBillingsController extends BaseController
{
    public function index($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->with('billings')->where('auxiliary05', $id)->first();
        return view('Shipments.OrderDeliveries.ShipmentBillings.index', compact('orderDelivery'));
    }


    public function store(CreateDeliveryRequest $request,$id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
       StorageShipmentBilling::create([
            'centerid' => $orderDelivery->centerid,
           'warehouseid' => $orderDelivery->warehouseid,
           'orderid' => $orderDelivery->id,
           'billingdocument' => $request['billingdocument'],
       ]);
       return $this->redirectBackSuccessMessage('Registro guardado con exito.');
    }


}
