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
            ->Shipmentid($request->get('shipmentid'))
            ->Carrier($request->get('carrierid'))
            ->CarrierIn($carriers)
            ->CustomerIn($customers)
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
                'shipmenttransport.auxiliary02 as statustransport',
            ]);
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
                'shipmenttransport.trucknumber', 'shipmenttransport.auxiliary03', 'shipmenttransport.auxiliary04',
            ])->first();
    }

    public function getModelDeliveryForPrintFormat($id)
    {
        return OrderDeliverie::controlAccessUser()->where('orderdeliveries.id', '=', $id)
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('shipmenttransport', 'orderdeliveries.id', '=', 'shipmenttransport.orderid')
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->select([
                'orderdeliveries.id', 'orderdeliveries.shipmentid', 'orderdeliveries.loadingdate', 'orderdeliveries.downloaddate',
                'orderdeliveries.confirmation', 'orderdeliveries.auxiliary05 as code', 'ordercustomers.name as customername',
                'ordercustomers.city', 'shipmenttransport.operatorname', 'shipmenttransport.phone', 'shipmenttransport.drivernumber',
                'shipmenttransport.boxnumber', 'shipmenttransport.trucknumber', 'shipmentcarriers.name as carriername',
                'shipmentcarriers.auxiliary04 as qr',
                'shipmentcarriers.auxiliary05 as logo',
                'shipmenttransport.auxiliary02 as statustransport',
            ])->first();
    }
}
