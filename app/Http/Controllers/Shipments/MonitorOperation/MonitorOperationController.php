<?php

namespace App\Http\Controllers\Shipments\MonitorOperation;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\MonitorTraffic\UpdateOperationRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentPallet;
use App\Models\Yard\StorageLocation;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationController extends BaseController
{

    public function index()
    {
        $this->authorize('monitoroperation.index');
        $status = $this->getStatusForSchema('KLE');
        $turns = $this->getTurns();
        $warehouses = $this->getWarehouseForSelect();
        $statusQuantitys = OrderDeliverie::controlAccessUser()->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->select(['statusid', DB::raw('COUNT(statusid) as total')])
            ->groupBy('statusid')->orderByDesc('total')->get();
        return view('Shipments.OrderMonitor.MonitorOperation.index',
            compact('status', 'turns', 'warehouses', 'statusQuantitys'));
    }

    public function getdataoperationmonitor(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->ForStatus($request->get('statusid'))
            ->Shipmentid($request->get('shipmentid'))
            ->Workday($request->get('workday'))
            ->Turn($request->get('turnid'))
            ->Warehouse($request->get('warehouseid'))
            ->leftjoin("storageturns as s", "orderdeliveries.turnid", "=", "s.id")
            ->leftjoin("systemstatus as s2", "orderdeliveries.statusid", "=", "s2.statusid")
            ->leftjoin("shipmentheader as s3", "orderdeliveries.id", "=", "s3.orderid")
            ->leftjoin("storagelocations as s4", "s3.locationid", "=", "s4.id")
            ->leftjoin("receiptunits as r2", "orderdeliveries.folionumber", "=", "r2.id")
            ->leftjoin("storagelocations as s5", "r2.locationid", "=", "s5.id")
            ->leftjoin("ordercustomers as o", "orderdeliveries.customerid", "=", "o.id")
            ->leftjoin("shipmentdataoperation as s6", "orderdeliveries.id", "=", "s6.orderid")
            ->leftjoin('shipmentcarriers as sc', 'orderdeliveries.carrierid', '=', 'sc.id')
            ->leftJoin('ordermonitor as or', 'orderdeliveries.id', '=', 'or.orderid')
            ->leftJoin('systemwarehouses as sw', 'orderdeliveries.warehouseid', '=', 'sw.warehouseid')
            ->whereNotIn("orderdeliveries.statusid", ['CA', 'DH'])
            ->select(["orderdeliveries.warehouseid", "orderdeliveries.id", 'sw.name as warehouse',
                'orderdeliveries.turnid',
                "orderdeliveries.workday", "s.name as turno", "orderdeliveries.shipmentid",
                "orderdeliveries.auxiliary03 as complemento",
                "s2.name as statusname", "s4.name as precarga", "s5.name as cortina", "or.outputdate",
                "o.name as customer", "o.city", "orderdeliveries.loadingdate",
                's6.comment01', 's6.comment02', 's6.comment03', 's3.locationid',
                "orderdeliveries.downloaddate", "s6.auxiliary01 as priority",
                "r2.entrydate", "r2.deliverdate", "s3.totalcontainers", 'r2.keydate as keys',
                "s3.totalpieces", "s3.totalweight as peso", "s6.auxiliary02 as armado",
                "orderdeliveries.auxiliary01 as dvnegocio", "orderdeliveries.auxiliary02 as planer",
                "s6.fullpallet", "s6.fullpicking", 's2.statusid', 'sc.carrierid as vendor'])
            ->addSelect(DB::raw("DATEDIFF(minute, GETDATE(),orderdeliveries.loadingdate) as minuterestantecarga"))
            ->addSelect(DB::raw("CONCAT(orderdeliveries.shipmentid,'|',orderdeliveries.auxiliary03) as embarque"));
        return DataTables::of($data)
            ->addColumn('btnoperation', 'Shipments.OrderMonitor.MonitorOperation.partials.btnoperation')
            ->editColumn('shtotalcontainers', 'Shipments.OrderMonitor.MonitorOperation.partials.statuspallets')
            ->editColumn('shtotalpieces', 'Shipments.OrderMonitor.MonitorOperation.partials.statuspick')
            ->addColumn('statusshipment', 'Shipments.OrderMonitor.MonitorOperation.partials.statusshipment')
            ->addColumn('statusunit', 'Shipments.OrderMonitor.MonitorOperation.partials.statusunit')
            ->addColumn('key', 'Shipments.OrderMonitor.MonitorOperation.partials.statuskey')
            ->addColumn('shipmentiactive', 'Shipments.OrderMonitor.AnalystTraffic.partials.shipment')
            ->rawColumns(['btnoperation', 'key', 'shtotalcontainers', 'shtotalpieces',
                'statusshipment', 'statusunit', 'shipmentiactive'])->toJson();
    }


    public function edit(OrderDeliverie $monitoroperation)
    {
        $materials = $this->getMaterials();
        $locationsStages = self::getLocationsStagesForCenter();
        $locations = self::getLocations();
        $orderDelivery = $monitoroperation;
        return view('Shipments.OrderMonitor.MonitorOperation.edit',
            compact('orderDelivery', 'materials', 'locationsStages', 'locations'));
    }

    private function getLocations()
    {
        $locations = StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->whereIn('storagelocations.statusid', ['E'])
            ->where('storagezones.zoneid', '=', 'ST')
            ->select(['storagelocations.name', 'storagelocations.id'])->get();
        return $locations;
    }

    public function getLocationsStagesForCenter()
    {
        $locations = StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->where('storagezones.zoneid', '=', 'ST')
            ->select(['storagelocations.name', 'storagelocations.id'])->get();
        return $locations;
    }

    private function getMaterials()
    {
        $materials = InventoryPartnumber::controlAccessUser()
            ->whereHas('category01', function ($q) {
                $q->where('description', 'TARIMA');
            })->select(['lngdsc', 'prtnum'])->get();
        return $materials;
    }

    public function update(UpdateOperationRequest $request, OrderDeliverie $monitoroperation)
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $monitoroperation->id)->first();
        $shipment->update($request->validated() + ['createuserid' => Auth::user()->id]);
        $this->updateBooleanAttributesFromRequest($request, $shipment, ['fullpallet', 'fullpicking']);
        self::addTypesPalletsToShipment($request, $shipment);
        return $this->redirectBackSuccess();
    }

    protected function updateBooleanAttributesFromRequest($request, $shipment, array $attributes)
    {
        foreach ($attributes as $attribute) {
            $shipment->$attribute = $request->has($attribute) ? 1 : null;
            $shipment->save();
        }
    }


    private function addTypesPalletsToShipment($request, $orderDelivery): void
    {
        $partnumbers = $request->input('prtnum');
        $registros = count($partnumbers) - 1;
        for ($i = 0; $i <= $registros; $i++) {
            if ($request['quantity'][$i] != '') {
                ShipmentPallet::create([
                    'centerid' => $orderDelivery->centerid,
                    'warehouseid' => $orderDelivery->warehouseid,
                    'orderid' => $orderDelivery->orderid,
                    'prtnum' => $request['prtnum'][$i],
                    'quantity' => $request['quantity'][$i],
                ]);
            }
        }
    }


}
