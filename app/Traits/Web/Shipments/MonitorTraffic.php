<?php

namespace App\Traits\Web\Shipments;

use App\Models\Shipments\OrderDeliverie;

trait MonitorTraffic
{
    public function getshipmentsformonitortraffic($request)
    {
        return OrderDeliverie::controlAccessUser()
            ->select([
                'orderdeliveries.id',
                'orderdeliveries.statusid',
                'orderdeliveries.shipmentid',
                'orderdeliveries.warehouseid',
                'orderdeliveries.centerid',
                'orderdeliveries.customerid',
                'orderdeliveries.carrierid',
                'orderdeliveries.folionumber',
                'orderdeliveries.turnid',
                'orderdeliveries.workday',
                'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate',
            ])
            ->with([
                'center:centerid,name',
                'warehouse:warehouseid,name',
                'turns:id,name',
                'header:id,locationid,orderid,totalcontainers,totalpieces',
                'folio:id,boxnumber,locationid,keydate,entrydate,ensnare',
                'customer:id,name,city',
                'carrier:id,name',
                'monitor:id,outputdate,orderid',
                'transport:id,auxiliary02,orderid',
                'status:statusid,name,auxiliary01',
            ])
            ->when($request->statusid, fn ($q) => $q->ForStatus($request->statusid))
            ->when($request->shipmentid, fn ($q) => $q->Shipmentid($request->shipmentid))
            ->when($request->workday, fn ($q) => $q->Workday($request->workday))
            ->when($request->turnid, fn ($q) => $q->Turn($request->turnid))
            ->when($request->warehouseid, fn ($q) => $q->Warehouse($request->warehouseid))
            ->whereNotIn('statusid', ['CA', 'DH']);
    }
}
