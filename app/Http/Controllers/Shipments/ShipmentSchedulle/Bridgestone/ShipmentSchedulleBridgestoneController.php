<?php

namespace App\Http\Controllers\Shipments\ShipmentSchedulle\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShipmentSchedulleBridgestoneController extends BaseController
{
    public function index()
    {
        $this->authorize('monitoroperation.index');
        $status = $this->getStatusForSchema('KLE');
        $warehouses = $this->getWarehouseForSelect();

        return view('Shipments.ShipmentSchedulle.Bridgestone.index', compact('status', 'warehouses'));
    }

    public function getDataSchedulleShipmentBridgestone(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->ForStatus($request->get('statusid'))
            ->Shipmentid($request->get('shipmentid'))
            ->Workday($request->get('workday'))
            ->leftJoin('ordercustomers as oc', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'oc.id')
                    ->on('orderdeliveries.centerid', '=', 'oc.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'oc.warehouseid');
            })
            ->leftJoin('shipmentcarriers as sc', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'sc.id')
                    ->on('orderdeliveries.centerid', '=', 'sc.centerid');
            })
            ->leftJoin('shipmenttypes as st', function ($join) {
                $join->on('orderdeliveries.shipmenttypeid', '=', 'st.id')
                    ->on('orderdeliveries.centerid', '=', 'st.centerid');
            })
            ->leftJoin('receiptunits as ru', function ($join) {
                $join->on('orderdeliveries.folionumber', '=', 'ru.id')
                    ->on('orderdeliveries.centerid', '=', 'ru.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'ru.warehouseid');
            })
            ->leftJoin('storagelocations as sl', function ($join) {
                $join->on('ru.locationid', '=', 'sl.id')
                    ->on('orderdeliveries.centerid', '=', 'sl.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'sl.warehouseid');
            })
            ->leftJoin('systemstatus as se', function ($join) {
                $join->on('orderdeliveries.statusid', '=', 'se.statusid')
                    ->on('orderdeliveries.schemaid', '=', 'se.schemaid');
            })
            ->leftJoin('shipmentheader as sh', function ($join) {
                $join->on('orderdeliveries.id', '=', 'sh.orderid')
                    ->on('orderdeliveries.centerid', '=', 'sh.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'sh.warehouseid');
            })
            ->leftJoin('storagelocations as sl2', function ($join) {
                $join->on('sh.locationid', '=', 'sl2.id')
                    ->on('sh.centerid', '=', 'sl2.centerid');
            })
            ->leftJoin('shipmenttrucks as t', function ($join) {
                $join->on('ru.truckid', '=', 't.id')
                    ->on('ru.centerid', '=', 't.centerid');
            })
            ->select([
                'orderdeliveries.centerid',
                'orderdeliveries.warehouseid',
                'orderdeliveries.id',
                'orderdeliveries.workday',
                'orderdeliveries.shipmentid',
                'oc.name as customername',
                'oc.city',
                'sc.name as carriername',
                't.name as truckname',
                'orderdeliveries.downloaddate as citaentrega',
                'st.name as shipmenttype',
                'sl.name as cortina',
                'se.name as status',
                'se.auxiliary01 as color',
                'ru.boxnumber',
                'ru.econumber',
                DB::raw("FORMAT(orderdeliveries.loadingdate, 'HH:mm') as eta"),
                'sh.totalpieces',
                'ru.id as folionumber',
                'sl2.name as stage',
                'ru.entrydate',
                'orderdeliveries.statusid',
            ])
            ->whereNotIn('orderdeliveries.statusid', ['CA'])
            ->orderBy('orderdeliveries.id', 'desc')->limit(200)->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Shipments.OrderMonitor.MonitorOperation.partials.btnoperation')
            ->rawColumns(['btn'])
            ->toJson();
    }
}
