<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchTotalQuantitySkuForLpnRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class SearchTotalQuantityForSkuInLpnController extends Controller
{
    use ResponseApi;
    public function index(SearchTotalQuantitySkuForLpnRequest $request)
    {
        $shipment = $this->getOrderDelivery($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getTotalQuantityForSkuForLpns', 'El número de embarque no existe, favor de validar.');
        }
        $total = $this->getTotalForSkuAndLpn($request,$shipment);
        return $this->getResponseSuccess('getTotalQuantityForSkuForLpns', $total);
    }

    public function getOrderDelivery($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
    }

    public function getTotalForSkuAndLpn($request,$shipment)
    {
        return DB::table('shipmentstock')->select(
            'shipmentstock.lpnid',
            'shipmentstock.partnumberid',
            DB::raw("CONCAT(i.prtnum, '-', i.lngdsc) as description"),
            DB::raw('SUM(quantity) as total')
        )
            ->leftJoin('inventorypartnumbers as i', 'shipmentstock.partnumberid', '=', 'i.id')
            ->where('lpnid', $request['lpnid'])
            ->where('shipmentstock.partnumberid', $request['partnumberid'])
            ->where('orderid', $shipment->id)
            ->groupBy('lpnid', 'i.prtnum', 'i.lngdsc', 'shipmentstock.partnumberid')
            ->get();
    }


}
