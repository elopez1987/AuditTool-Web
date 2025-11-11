<?php

namespace App\Traits\Web\Shipments;

use App\Models\Shipments\OrderDeliverie;
use DB;

trait MonitorTraffic
{
    public function getDataMonitor($request)
    {
        return OrderDeliverie::controlAccessUser()
            ->ForStatus($request->get('statusid'))
            ->Shipmentid($request->get('shipmentid'))
            ->Workday($request->get('workday'))
            ->Turn($request->get('turnid'))
            ->Warehouse($request->get('warehouseid'))
            ->leftjoin('storageturns as s', 'orderdeliveries.turnid', '=', 's.id')
            ->leftjoin('systemstatus as s2', 'orderdeliveries.statusid', '=', 's2.statusid')
            ->leftjoin('shipmentheader as s3', 'orderdeliveries.id', '=', 's3.orderid')
            ->leftjoin('storagelocations as s4', 's3.locationid', '=', 's4.id')
            ->leftjoin('receiptunits as r2', 'orderdeliveries.folionumber', '=', 'r2.id')
            ->leftjoin('shipmentcarriers as sh', 'orderdeliveries.carrierid', '=', 'sh.id')
            ->leftjoin('storagelocations as s5', 'r2.locationid', '=', 's5.id')
            ->leftjoin('ordercustomers as o', 'orderdeliveries.customerid', '=', 'o.id')
            ->leftjoin('shipmentdataoperation as s6', 'orderdeliveries.id', '=', 's6.orderid')
            ->leftJoin('shipmenttransport as st', 'orderdeliveries.id', 'st.orderid')
            ->leftJoin('ordermonitor as or', 'orderdeliveries.id', '=', 'or.orderid')
            ->leftJoin('shipmentseals as ssl', 'orderdeliveries.id', '=', 'ssl.orderid')
            ->whereNotIn('orderdeliveries.statusid', ['CA', 'DH'])
            ->select(['orderdeliveries.warehouseid', 'orderdeliveries.id',
                'orderdeliveries.workday', 's.name as turno', 'orderdeliveries.shipmentid', 'orderdeliveries.auxiliary03 as complemento',
                's2.name as statusname', 's4.name as precarga', 's5.name as cortina', 'st.phone', 'st.drivernumber',
                'o.name as customer', 'o.city', 'orderdeliveries.loadingdate', 'r2.keydate as keys',
                'orderdeliveries.downloaddate', 'r2.entrydate', 'r2.deliverdate',
                'orderdeliveries.auxiliary01 as dvnegocio', 'orderdeliveries.auxiliary02 as planer',
                's6.fullpallet', 's6.fullpicking', 's2.statusid', 'st.auxiliary02 as datatransport', 'r2.operatorname as r2operator',
                'sh.name as carriername', 'r2.boxnumber as r2boxnumber', 'o.timetransfer', 'r2.autorizaoutputdate', 'r2.id as fpatios',
                's3.comment', 's6.auxiliary02 as armado', 'or.outputdate as maxoutputtransport', 's3.totalpieces as cajas', 's3.totalcontainers as pallets',
                's3.totalweight', 'ssl.containerseal01', 'ssl.containerseal02', 'ssl.receivername', 'ssl.containerseal03',
                'st.operatorname', 'st.boxnumber', 'st.trucknumber', 'st.auxiliary03', 'st.auxiliary04',
            ])
            ->addSelect(DB::raw('DATEDIFF(hour, GETDATE(), DATEADD(HH, - (o.timetransfer / 60), orderdeliveries.downloaddate))  as hrestante'))
            ->addSelect(DB::raw("CONCAT(orderdeliveries.shipmentid,'|',orderdeliveries.auxiliary03) as embarque"));
    }
}
