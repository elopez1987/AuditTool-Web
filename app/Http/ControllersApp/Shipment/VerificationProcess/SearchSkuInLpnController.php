<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchShipmenVerificationRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class SearchSkuInLpnController extends Controller
{
    use ResponseApi;

    public function index(SearchShipmenVerificationRequest $request)
    {
        $shipment = $this->getOrderDelivery($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getSkuForLpns', 'El número de embarque no existe, favor de validar.');
        }
        $partnumbers = $this->getPartNumbersStock($request, $shipment);
        return $this->getResponseSuccess('getSkuForLpns', $partnumbers);
    }

    public function getPartNumbersStock($request, $shipment)
    {
        return DB::table('shipmentstock')->select(
            'shipmentstock.partnumberid',
            DB::raw("CONCAT(i.prtnum, '-', i.lngdsc) as description"),
        )
            ->leftJoin('inventorypartnumbers as i', 'shipmentstock.partnumberid', '=', 'i.id')
            ->where('lpnid', $request['lpnid'])
            ->where('orderid', $shipment->id)
            ->groupBy('lpnid', 'i.prtnum', 'i.lngdsc', 'shipmentstock.partnumberid')
            ->distinct()->get();
    }

    public function getOrderDelivery($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
    }

}
