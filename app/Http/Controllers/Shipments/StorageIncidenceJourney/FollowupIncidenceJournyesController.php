<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Traits\Web\Shipments\QuerysIncidencesJourneys;
use DataTables;
use Illuminate\Http\Request;

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

        return DataTables::collection($data)
            ->addColumn('btn', 'Shipments.FollowupIncidences.partials.buttons')
            ->addColumn('keystatus', 'Shipments.FollowupIncidences.partials.keystatus')
            ->addColumn('keytransport', 'Shipments.FollowupIncidences.partials.keytransport')
            ->editColumn('statuspod', function (StorageIncidenceJourney $storageIncidenceJourney) {
                return '<i class="ti ti-' . ($storageIncidenceJourney->statuspod === 'CON' ? 'check' : 'close') . '" aria-hidden="true"></i>';
            })
            ->rawColumns(['btn', 'statuspod', 'keystatus', 'keytransport'])->toJson();
    }

    public function getIncidence(Request $request)
    {
        return StorageIncidenceJourney::query()
            ->distinct()
            ->ForShipment($request->get('shipmentid'))
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
                'storageincidencejourneys.statusdevid',
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
            ->orderBy('storageincidencejourneys.folioid', 'desc')
            ->limit(100)
            ->get();
    }
}
