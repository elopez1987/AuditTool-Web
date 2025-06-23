<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;

class RequestLetterController extends BaseController
{
    public function requestletter($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $shipment->requestletter = now();
        $shipment->save();
        return $this->redirectBackSuccess();
    }

    public function responseletter($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $shipment->responseletter = now();
        $shipment->save();
        return $this->redirectBackSuccess();
    }

}
