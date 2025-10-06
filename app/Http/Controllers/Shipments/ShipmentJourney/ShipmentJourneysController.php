<?php

namespace App\Http\Controllers\Shipments\ShipmentJourney;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShipmentJourneysController extends BaseController
{
    public function index()
    {
        $carriers = $this->getCarriersForSelect();

        return view(
            'Shipments.ShipmentJourneys.index',
            compact('carriers')
        );
    }

    public function getDataJourney(Request $request)
    {
        $data = ShipmentJourney::controlAccessUser()
            ->rightJoin('orderdeliveries', 'orderdeliveries.id', '=', 'shipmentjourneys.orderid')
            ->leftjoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('shipmentheader', 'orderdeliveries.id', '=', 'shipmentheader.orderid')
            ->leftjoin('receiptunits', 'orderdeliveries.folionumber', '=', 'receiptunits.id')
            ->leftjoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftjoin('systemstatus', 'shipmentjourneys.statusid', '=', 'systemstatus.statusid')
            ->leftjoin('ordermonitor', 'orderdeliveries.id', '=', 'ordermonitor.orderid')
            ->leftjoin('storagecategoryincidencedetails', 'shipmentjourneys.razonoutputid', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->select(['orderdeliveries.shipmentid', 'shipmentcarriers.name as carriername', 'receiptunits.boxnumber',
                DB::raw('UPPER(ordercustomers.name) as customername'),
                DB::raw('UPPER(ordercustomers.city) as city'),
                'receiptunits.autorizaoutputdate', 'orderdeliveries.downloaddate', 'systemstatus.name as journeystatus',
                'receiptunits.outputdate as outputdateunitreal', 'receiptunits.entrydate as entrydateunit',
                'ordermonitor.outputdate as outputdatestimate', 'systemstatus.statusid', 'shipmentjourneys.id',
                'shipmentjourneys.location', 'shipmentjourneys.eta', 'shipmentjourneys.arrivedate', 'receiptunits.entrydate',
                'shipmentjourneys.receiptenddate', 'storagecategoryincidencedetails.description as razonoutputname', 'shipmentheader.goodissuedate',
                'shipmentjourneys.auxiliary01',
                'shipmentjourneys.auxiliary02',
                'shipmentjourneys.auxiliary03',
                'shipmentjourneys.auxiliary04',
                'orderdeliveries.loadingdate',
            ])
            ->Shipmentid($request->get('shipmentid'))
            ->ForCarrier($request->get('carrierid'))
            ->ForCustomer($request->get('customername'))
            ->whereNotIn('shipmentjourneys.statusid', ['ET'])
            ->latest('orderdeliveries.shipmentid')
            ->take(100)
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Shipments.ShipmentJourneys.partials.buttons')
            ->addColumn('statusunit', 'Shipments.ShipmentJourneys.partials.statusunit')
            ->rawColumns(['btn', 'statusunit'])->toJson();
    }

    public function edit(ShipmentJourney $shipmentjourney)
    {
        $status = SystemStatus::whereSchemaid('SHJ')->select('name', 'statusid')->get();
        $razonoutput = StorageCategoryIncidenceDetail::controlAccessUser()->where('statusid', 'E')
            ->where('auxiliary01', 'SAL')
            ->select(['categoryincdetailid', 'description'])
            ->get();

        return view(
            'Shipments.ShipmentJourneys.edit',
            compact('shipmentjourney', 'status', 'razonoutput')
        );
    }

    public function update(Request $request, ShipmentJourney $shipmentjourney)
    {
        $shipmentjourney->update($request->all());

        return $this->successResponse('shipmentjourney.index');
    }
}
