<?php

namespace App\Traits\Web\Shipments\ShipmentTransport;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;

trait QueryShipmentTransport
{
    public function getShipmentInProcess($request)
    {
        $carriers = $this->getCarrierForUser();
        $customers = $this->getCustomerForUser();

        return OrderDeliverie::controlAccessUser()
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('shipmenttransport', 'orderdeliveries.id', '=', 'shipmenttransport.orderid')
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA', 'EJ', 'FO', 'IF', 'DT', 'SO'])
            ->Shipmentid($request->input('shipmentid'))
            ->Carrier($request->input('carrierid'))
            ->select([
                'orderdeliveries.id',
                'orderdeliveries.shipmentid',
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
            ])
            ->when(! empty($carriers), function ($q) {
                $q->whereIn('orderdeliveries.carrierid', $carriers);
            })
            ->when(! empty($customers), function ($q) {
                $q->whereIn('orderdeliveries.customerid', $customers);
            });
    }

    public function getModelTransportEdit($id)
    {
        return StorageShipmentTransport::where('orderid', $id)
            ->leftJoin('orderdeliveries', 'shipmenttransport.orderid', '=', 'orderdeliveries.id')
            ->select([
                'orderdeliveries.shipmentid', 'orderdeliveries.workday', 'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate', 'orderdeliveries.id',
                'shipmenttransport.operatorname', 'shipmenttransport.drivernumber',
                'shipmenttransport.phone', 'shipmenttransport.boxnumber',
                'shipmenttransport.trucknumber', 'shipmenttransport.ecobox', 'shipmenttransport.ecotruck',
            ])->first();
    }
}
