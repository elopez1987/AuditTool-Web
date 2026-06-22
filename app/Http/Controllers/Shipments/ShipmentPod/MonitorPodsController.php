<?php

namespace App\Http\Controllers\Shipments\ShipmentPod;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\ShipmentPod\CreateShipmentPodRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Shipments\StorageShipmentsPods;
use App\Traits\Web\General\EvidencePhotoTrait;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MonitorPodsController extends BaseController
{
    use EvidencePhotoTrait;

    public function index()
    {
        $this->authorize('monitorpods.index');
        $status = SystemStatus::whereSchemaid('POD')->whereIn('statusid', ['PED', 'CON'])
            ->select(['name', 'statusid'])->get();

        return view('Shipments.MonitorPods.index', compact('status'));
    }

    public function create()
    {
        $this->authorize('monitorpods.create');

        return view('Shipments.MonitorPods.create');
    }

    public function store(CreateShipmentPodRequest $request)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
        ShipmentPod::updateOrCreate([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
        ]);

        return $this->redirectBackSuccessMessage('Embarque agregado con exito.');
    }

    public function show($id)
    {
        $this->authorize('monitorpods.show');
        $shipment = OrderDeliverie::findOrFail($id);
        $files = StorageShipmentsPods::where('orderid', $id)->get();
        $status = SystemStatus::whereSchemaid('POD')->whereNotIn('statusid', ['PED', 'CON'])
            ->select(['name', 'statusid'])->get();

        return view('Shipments.MonitorPods.show', compact('shipment', 'files', 'status'));
    }

    public function getData(Request $request)
    {
        $customers = $this->getCustomerForUser();

        $carrier = AccessCarrier::where('username', Auth::user()->username)
            ->pluck('carrierid');

        return DB::table('shipmentpods')
            ->leftJoin('orderdeliveries', function ($join) {
                $join->on('shipmentpods.orderid', '=', 'orderdeliveries.id')
                    ->on('shipmentpods.centerid', '=', 'orderdeliveries.centerid');
            })
            ->leftJoin('ordercustomers as o2', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'o2.id')
                    ->on('orderdeliveries.centerid', '=', 'o2.centerid');
            })
            ->leftJoin('shipmentcarriers as s2', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 's2.id')
                    ->on('orderdeliveries.centerid', '=', 's2.centerid');
            })
            ->leftJoin('systemstatus as s3', 'shipmentpods.statusid', '=', 's3.statusid')
            ->where('orderdeliveries.statusid', 'DH')
            ->select([
                'shipmentpods.id',
                'shipmentpods.statusid',
                'orderdeliveries.id as orderid',
                'orderdeliveries.shipmentid',
                DB::raw('UPPER(o2.name) as customername'),
                DB::raw('UPPER(o2.city) as city'),
                's2.name as carriername',
                'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate',
                's3.name as status',
            ])
            ->when($request->filled('shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            })
            ->when($carrier->isNotEmpty(), function ($q) use ($carrier) {
                $q->whereIn('orderdeliveries.carrierid', $carrier);
            })
            ->when(! empty($customers), function ($q) use ($customers) {
                $q->whereIn('orderdeliveries.customerid', $customers);
            })
            ->when($request->filled('statusid'), function ($q) use ($request) {
                $q->where('shipmentpods.statusid', $request->input('statusid'));
            });
    }

    public function getDataMonitorPods(Request $request)
    {
        $data = $this->getData($request);

        return DataTables::query($data)
            ->addColumn('btn', 'Shipments.MonitorPods.partials.buttons')
            ->filterColumn('shipmentid', function ($query, $keyword) {
                $query->where('orderdeliveries.shipmentid', 'like', "%{$keyword}%");
            })
            ->filterColumn('customername', function ($query, $keyword) {
                $query->whereRaw('UPPER(o2.name) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('city', function ($query, $keyword) {
                $query->whereRaw('UPPER(o2.city) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('carriername', function ($query, $keyword) {
                $query->where('s2.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('s3.name', 'like', "%{$keyword}%");
            })
            ->rawColumns(['btn'])
            ->toJson();
    }
}
