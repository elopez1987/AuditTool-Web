<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchShipmenVerificationRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;

class SearchShipmenForVerificationtController extends BaseController
{
    use ResponseApi;

//    Consulta el embarque para el proceso de verificacion
    public function index(SearchShipmenVerificationRequest $request)
    {
        $order = OrderDeliverie::controlAccessUser()
            ->where('orderdeliveries.shipmentid', $request['shipmentid'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'IF', 'FO', 'EJ', 'AP', 'DO'])->first();

        if (empty($order)) {
            return $this->getResponseWithErrors('getDataShipment', 'No existe número de embarque o se encuentra en estatus no permitido.');
        }

        if (empty($order->check->startdate)) {
            return $this->getResponseWithErrors('registerCheckToShipment', 'La verificación no esta iniciada,favor de iniciar el proceso');
        }
        if (!empty($order->check->finishdate)) {
            return $this->getResponseWithErrors('registerCheckToShipment', 'La verificación ya finalizó.');
        }
        return $this->getResponseSuccess('getDataShipmentCheck', [
            'customername' => $order->customer ? $order->customer->name : '',
            'orderid' => $order->id,
            'shipmentid' => $order->shipmentid,
            'tarima' => $order->operation ? $order->operation->auxiliary03 : '',
            'truckid' => $order->transport->truck ? $order->transport->truck->name : '',
            'footprints' => $order->transport->truck ? $order->transport->truck->auxiliary03 : ''
        ]);

    }


}
