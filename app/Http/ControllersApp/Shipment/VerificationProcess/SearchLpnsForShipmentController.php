<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchShipmenVerificationRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Traits\ResponseApi;

class SearchLpnsForShipmentController extends Controller
{
    use ResponseApi;

    public function index(SearchShipmenVerificationRequest $request)
    {
        $shipment = $this->getOrderDelivery($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataShipmetAndLpns', 'El número de embarque no existe, favor de validar.');
        }

        $lpns = $this->getShipmentContainers($shipment);

        return $this->getResponseSuccess('getDataShipmetAndLpns', [
            'customername' => $shipment->customer->name, 'lpns' => $lpns]);
    }

    public function getOrderDelivery($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
    }

    public function getShipmentContainers(OrderDeliverie $shipment)
    {
        $lpns = ShipmentContainer::controlAccessUser()->where('orderid', $shipment->id)
            ->select(['lpnid'])->distinct()->get();
        return $lpns;
    }


}
