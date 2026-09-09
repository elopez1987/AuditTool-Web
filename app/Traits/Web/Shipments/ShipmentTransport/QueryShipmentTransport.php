<?php

namespace App\Traits\Web\Shipments\ShipmentTransport;

use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessCustomer;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use Auth;

trait QueryShipmentTransport
{
    public function getShipmentInProcess($request)
    {
        $carriers = AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
        $customers = SecurityAccessCustomer::where('username', '=', Auth::user()->username)
            ->select('customerid')->get()->toArray();
        $confirmation = $request->input('confirmation');

        return OrderDeliverie::controlAccessUser()
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('shipmenttransport', 'orderdeliveries.id', '=', 'shipmenttransport.orderid')
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('users', 'shipmenttransport.createuser', '=', 'users.id')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA', 'EJ', 'FO', 'IF', 'DT', 'SO'])
            ->where('shipmenttransport.auxiliary04', 'OrderDelivery')
            ->Shipmentid($request->input('shipmentid'))
            ->Carrier($request->input('carrierid'))
            ->select([
                'orderdeliveries.id',
                'orderdeliveries.shipmentid',
                'orderdeliveries.carrierid',
                'orderdeliveries.customerid',
                'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate',
                'orderdeliveries.confirmation',
                'ordercustomers.name as customername',
                'ordercustomers.city',
                'shipmentcarriers.name as carriername',
                'systemstatus.name as statusshipment',
                'shipmenttransport.operatorname',
                'shipmenttransport.boxnumber',
                'shipmenttransport.trucknumber',
                'shipmenttransport.has_data as statustransport',
                'users.name as ultimousuario',
            ])
            ->when(! empty($carriers), function ($q) use ($carriers) {
                $q->whereIn('orderdeliveries.carrierid', $carriers);
            })
            ->when(! empty($confirmation), function ($q) use ($confirmation) {
                $q->where('orderdeliveries.confirmation', $confirmation);
            })
            ->when(! empty($customers), function ($q) use ($customers) {
                $q->whereIn('orderdeliveries.customerid', $customers);
            });
    }

    public function getModelTransportEdit($id)
    {
        return StorageShipmentTransport::where('orderid', $id)
            ->leftJoin('orderdeliveries', 'shipmenttransport.orderid', '=', 'orderdeliveries.id')
            ->where('shipmenttransport.auxiliary04', 'OrderDelivery')
            ->select([
                'orderdeliveries.shipmentid', 'orderdeliveries.workday', 'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate', 'orderdeliveries.id',
                'shipmenttransport.operatorname', 'shipmenttransport.drivernumber',
                'shipmenttransport.phone', 'shipmenttransport.boxnumber',
                'shipmenttransport.trucknumber', 'shipmenttransport.ecobox', 'shipmenttransport.ecotruck',
            ])->first();
    }
}
