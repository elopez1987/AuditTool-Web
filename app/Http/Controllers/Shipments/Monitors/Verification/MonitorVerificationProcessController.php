<?php

namespace App\Http\Controllers\Shipments\Monitors\Verification;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentCheck;
use Illuminate\Support\Facades\DB;

class MonitorVerificationProcessController extends BaseController
{
    public function statusshipmentchek()
    {
        $this->authorize('statusshipmentchek');
        return view('Shipments.MonitorShipmentCheck.index');
    }

    public function getDataStatusShipmentchek()
    {
        $data = ShipmentCheck::controlAccessUser()->whereNotNull('startdate')
            ->whereNull('finishdate')
            ->join('orderdeliveries', 'shipmentcheck.orderid', '=', 'orderdeliveries.id')
            ->join('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->join('shipmentcontrol', 'orderdeliveries.id', '=', 'shipmentcontrol.orderid')
            ->join('users', 'shipmentcheck.createuser', 'users.username')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->select(['orderdeliveries.shipmentid', 'ordercustomers.name as customername',DB::raw('UPPER(users.name) as username'),'orderdeliveries.id'])
            ->selectRaw(' sum(shipmentcontrol.quantityrequested) as totalpedido, sum(shipmentcontrol.quantitytrue) as verificado')
            ->groupBy(['orderdeliveries.shipmentid', 'ordercustomers.name', 'users.name','orderdeliveries.id'])
            ->get()->toArray();
        return response()->json($data);
    }
}
