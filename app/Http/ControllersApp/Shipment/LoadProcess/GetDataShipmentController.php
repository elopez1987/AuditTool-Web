<?php

namespace App\Http\ControllersApp\Shipment\LoadProcess;

use App\Http\Controllers\Controller;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class GetDataShipmentController extends Controller
{
    use ResponseApi;


    public function index(Request $request)
    {
        if (empty($request['shipmentid'])) {
            return $this->getResponseWithErrors('getDataShipmentToLoad', 'El número de embarque es requerido');
        }
        $order = OrderDeliverie::controlAccessUser()
            ->where('orderdeliveries.shipmentid', '=', $request['shipmentid'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])->first();
        if (empty($order)) {
            return $this->getResponseWithErrors('getDataShipmentToLoad', 'No existe número de embarque.');
        }
        if ($order->statusid != 'IP') {
            return $this->getResponseWithErrors('getDataShipmentToLoad', 'Estatus incorrecto a CARGANDO.');
        }
        return $this->getResponseSuccess('getDataShipmentToLoad', [
            'customername' => $order->customer ? $order->customer->name : '',
            'orderid' => $order->id,
            'shipmentid' => $order->shipmentid,
            'truckid' => $order->transport->truck ? $order->transport->truck->name : '',
            'photos' => $order->transport->truck ? $order->transport->truck->auxiliary02 : '',
        ]);
    }


}
