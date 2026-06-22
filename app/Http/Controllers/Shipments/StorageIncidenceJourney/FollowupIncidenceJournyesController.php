<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Traits\Web\Shipments\QuerysIncidencesJourneys;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowupIncidenceJournyesController extends BaseController
{
    use QuerysIncidencesJourneys;

    public function index()
    {
        return view('Shipments.FollowupIncidences.index');
    }

    public function getDataIncidences(Request $request)
    {
        $data = $this->getIncidence($request);

        return DataTables::query($data)
            ->addColumn('btn', 'Shipments.FollowupIncidences.partials.buttons')
            ->addColumn('keystatus', 'Shipments.FollowupIncidences.partials.keystatus')
            ->addColumn('keytransport', 'Shipments.FollowupIncidences.partials.keytransport')
            ->editColumn('statuspod', function ($row) {
                return '<i class="ti ti-' . ($row->statuspod === 'CON' ? 'check' : 'close') . '" aria-hidden="true"></i>';
            })
            ->rawColumns(['btn', 'statuspod', 'keystatus', 'keytransport'])->toJson();
    }

    public function getIncidence(Request $request)
    {
        return DB::table('storageincidencejourneys')
            ->distinct()
            ->select([
                'storageincidencejourneys.centerid',
                'storageincidencejourneys.warehouseid',
                'storageincidencejourneys.folioid',
                'storageincidencejourneys.statusid',
                'storageincidencejourneys.statusdevid',
                'storageincidencejourneys.responsedate',
                'orderdeliveries.shipmentid',
                'storageincidencejourneys.carrierid',
                'shipmentcarriers.name as carrier',
                'ordercustomers.name as customer',
                'ordercustomers.city',
                'orderdeliveries.downloaddate as download',
                'storageincidencejourneys.created_at as created',
                'systemstatus.name as status',
                'shipmentpods.statusid as statuspod',
                'storageincidencejourneys.statuskeyid',
                'storageincidencejourneys.statuskeytrid',
                'statusdev.name as statusdev',
            ])
            ->leftJoin('orderdeliveries', function ($join) {
                $join->on('storageincidencejourneys.orderid', '=', 'orderdeliveries.id')
                    ->on('storageincidencejourneys.centerid', '=', 'orderdeliveries.centerid')
                    ->on('storageincidencejourneys.warehouseid', '=', 'orderdeliveries.warehouseid');
            })
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'ordercustomers.warehouseid');
            })
            ->leftJoin('systemstatus', function ($join) {
                $join->on('storageincidencejourneys.statusid', '=', 'systemstatus.statusid')
                    ->on('storageincidencejourneys.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('shipmentcarriers', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
                    ->on('orderdeliveries.centerid', '=', 'shipmentcarriers.centerid');
            })
            ->leftJoin('shipmentpods', function ($join) {
                $join->on('storageincidencejourneys.orderid', '=', 'shipmentpods.orderid')
                    ->on('storageincidencejourneys.centerid', '=', 'shipmentpods.centerid')
                    ->on('storageincidencejourneys.warehouseid', '=', 'shipmentpods.warehouseid');
            })
            ->leftJoin('systemstatus as statusdev', function ($join) {
                $join->on('storageincidencejourneys.statusdevid', '=', 'statusdev.statusid')
                    ->on('storageincidencejourneys.schemadevid', '=', 'statusdev.schemaid');
            })
            ->where('storageincidencejourneys.statusid', 'CD')
            ->where('orderdeliveries.statusid', 'DH')
            ->when($request->filled('orderdeliveries.shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            });
    }
}
