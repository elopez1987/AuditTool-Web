<?php

namespace App\Http\Controllers\Shipments\Monitors\Transport;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Shipments\OrderDeliverie;
use Auth;
use Illuminate\Http\Request;

class MonitorShipmentStatusController extends BaseController
{
    public function index()
    {
        $this->authorize('shipmentstatustransport');
        return view('Shipments.MonitorStatusTransport.index');
    }

    public function getStatusShipmentTransport(Request $request)
    {
        $carrier = AccessCarrier::where('username', Auth::user()->username)->select('carrierid')->get()->toArray();
        $data = OrderDeliverie::controlAccessUser()
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', 'shipmentcarriers.id')
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', 'ordercustomers.id')
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', 'systemstatus.statusid')
            ->leftJoin('receiptunits','orderdeliveries.folionumber','receiptunits.id')
            ->Shipmentid($request->get('shipmentid'))
            ->CarrierIn($carrier)
            ->select([
                'ordercustomers.name as customername',
                'ordercustomers.city',
                'systemstatus.name as status',
                'shipmentcarriers.name as carriername',
                'orderdeliveries.shipmentid',
                'orderdeliveries.id',
                'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate',
                'receiptunits.operatorname',
            ])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->get();
        $data = json_encode($data);
        return response()->json($data);
    }







}
