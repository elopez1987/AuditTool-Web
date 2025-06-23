<?php

namespace App\Http\Controllers\Services\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Services\ServiceShipmentContainer;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventShipmentController extends Controller
{
    use ResponseApi;

    private ServiceShipmentContainer $serviceShipmentContainer;

    public function __construct(ServiceShipmentContainer $serviceShipmentContainer)
    {
        $this->serviceShipmentContainer = $serviceShipmentContainer;
    }

    public function getShipmentForLoadContainers(Request $request)
    {
        if ($request['shipmentid'] == null) {
            return $this->getResponseWithErrors('getShipmentForLoadContainers', 'El número de embarque es obligatorio.');
        }
        $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
            ->where('statusid', '=', 'IP')->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getShipmentForLoadContainers', 'El número de embarque no existe o es diferente a cargando.');
        }
        $data = $this->getShipmentForLoadContainer($shipment->shipmentid);
        return $this->getResponseSuccess('getShipmentForLoadContainers', $data);
    }

    public function saveContainerInShipmentLoad(Request $request)
    {
        if ($request['shipmentid'] == null || $request['lpnid'] == null) {
            return $this->getResponseWithErrors('saveContainerInShipmentLoad',
                'El número de embarque,Lpn y Foto son valores obligatorios.');
        }
        $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
            ->where('statusid', '=', 'IP')->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('saveContainerInShipmentLoad',
                'El número de embarque no existe o es diferente a cargando.');
        }
        $container = ShipmentContainer::where('orderid', $shipment->id)
            ->where('lpnid', $request['lpnid'])->first();
        if (empty($container)) {
            return $this->getResponseWithErrors('saveContainerInShipmentLoad',
                'El número de lpn no corresponde al embarque.');
        }

        if ($container->statusid == 'SC') {
            return $this->getResponseWithErrors('saveContainerInShipmentLoad',
                'El número de lpn ya fue escaneado.');
        }

        $this->serviceShipmentContainer->updateContainerForLoad($container);
        $this->serviceShipmentContainer->createNewFileEvidence($request['file'], $shipment);
        $totalScan = $this->serviceShipmentContainer->getTotalContainerForStatusSC($shipment->id);

        return $this->getResponseSuccess('saveContainerInShipmentLoad', ['totalScan' => $totalScan]);
    }


    public function getShipmentForLoadContainer($shipmentid)
    {
        return DB::table('orderdeliveries')
            ->select('orderdeliveries.shipmentid', 'ordercustomers.name as customer', DB::raw("count(shipmentcontainers.lpnid) as pallets"))
            ->join('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid');
            })
            ->join('shipmentcontainers', function ($join) {
                $join->on('orderdeliveries.id', '=', 'shipmentcontainers.orderid')
                    ->on('orderdeliveries.centerid', '=', 'shipmentcontainers.centerid');
            })
            ->where('shipmentid', '=', $shipmentid)
            ->groupBy('orderdeliveries.shipmentid', 'ordercustomers.name')
            ->get();
    }


}
