<?php

namespace App\Http\Controllers\Shipments\Monitors\Operation;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationHenkelController extends BaseController
{
    public function monitoroperationhenkel()
    {
        $this->authorize('monitoroperationhenkel');

        return view('Shipments.MonitorShipments.MonitorOperation.index');
    }

    public function getDataMonitorOperation()
    {
        $data = $this->getOrderDeliveries();

        return DataTables::of($data)
            ->addColumn('stayskey', function ($data) {
                return Carbon::now()->diff($data->keydate)->format('%H:%i h/m');
            })
            ->editColumn('status', 'Shipments.MonitorShipments.MonitorOperation.statusshipment')
            ->editColumn('keydate', 'Shipments.MonitorShipments.MonitorOperation.keydate')
            ->editColumn('statustransport', 'Shipments.MonitorShipments.MonitorOperation.statustransport')
            ->editColumn('stays', 'Shipments.MonitorShipments.MonitorOperation.stays')
            ->editColumn('comment', 'Shipments.MonitorShipments.MonitorOperation.comment')
            ->editColumn('outputdate', 'Shipments.MonitorShipments.MonitorOperation.outputdate')
            ->rawColumns(['stayskey', 'stays', 'status', 'keydate', 'statustransport', 'outputdate', 'comment'])
            ->toJson();
    }

    public function getOrderDeliveries()
    {
        return OrderDeliverie::controlAccessUser()
            ->select([
                'orderdeliveries.shipmentid',
                DB::raw('UPPER(ordercustomers.name) as customer'),
                DB::raw('UPPER(ordercustomers.city) as city'),
                'shipmentcarriers.name as carrier',
                'orderdeliveries.statusid as statusshipment',
                'orderdeliveries.downloaddate',
                'shipmentdataoperation.comment02 as comment',
                'systemstatus.name as status',
                'receiptunits.statusid as statustrans',
                'systemstatus2.name as statustransport',
                'receiptunits.entrydate',
                'receiptunits.keydate',
                'ordermonitor.outputdate',
                DB::raw('DATEDIFF(minute, receiptunits.entrydate, GETDATE()) as stay'),
            ])
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid');
            })
            ->leftJoin('shipmentcarriers', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
                    ->on('orderdeliveries.centerid', '=', 'shipmentcarriers.centerid');
            })
            ->leftJoin('systemstatus', function ($join) {
                $join->on('orderdeliveries.statusid', '=', 'systemstatus.statusid')
                    ->on('orderdeliveries.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('receiptunits', function ($join) {
                $join->on('orderdeliveries.folionumber', '=', 'receiptunits.id')
                    ->on('orderdeliveries.centerid', '=', 'receiptunits.centerid');
            })
            ->leftJoin('systemstatus as systemstatus2', function ($join) {
                $join->on('receiptunits.statusid', '=', 'systemstatus2.statusid')
                    ->on('receiptunits.schemaid', '=', 'systemstatus2.schemaid');
            })
            ->leftJoin('ordermonitor', function ($join) {
                $join->on('orderdeliveries.id', '=', 'ordermonitor.orderid')
                    ->on('orderdeliveries.centerid', '=', 'ordermonitor.centerid');
            })
            ->leftJoin('shipmentdataoperation', function ($join) {
                $join->on('orderdeliveries.id', '=', 'shipmentdataoperation.orderid')
                    ->on('orderdeliveries.centerid', '=', 'shipmentdataoperation.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'shipmentdataoperation.warehouseid');
            })
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->orderBy('orderdeliveries.loadingdate', 'desc')
            ->get();
    }
}
