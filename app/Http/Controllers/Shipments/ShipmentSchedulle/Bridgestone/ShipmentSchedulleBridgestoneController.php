<?php

namespace App\Http\Controllers\Shipments\ShipmentSchedulle\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\SecurityAccessWarehouse;
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
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        $data = DB::table('orderdeliveries')
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
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereIn('orderdeliveries.warehouseid', $warehouses)
            ->whereNotIn('orderdeliveries.statusid', ['CA'])
            ->when($request->filled('statusid'), function ($q) use ($request) {
                $q->where('orderdeliveries.statusid', $request->input('statusid'));
            })
            ->when($request->filled('shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            })
            ->when($request->filled('workday'), function ($q) use ($request) {
                $q->whereDate('orderdeliveries.workday', $request->input('workday'));
            });

        return DataTables::query($data)
            ->addColumn('btn', 'Shipments.OrderMonitor.MonitorOperation.partials.btnoperation')
            ->filterColumn('customername', function ($query, $keyword) {
                $query->where('oc.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('carriername', function ($query, $keyword) {
                $query->where('sc.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('shipmenttype', function ($query, $keyword) {
                $query->where('st.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('se.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('truckname', function ($query, $keyword) {
                $query->where('t.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('eta', function ($query, $keyword) {
                $query->whereRaw("FORMAT(orderdeliveries.loadingdate, 'HH:mm') like ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['btn'])
            ->toJson();
    }
}
