<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Models\Catalogs\StorageTurn;
use App\Models\Configuration\SystemStatus;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentStage;
use App\Models\Yard\StorageLocation;
use App\Traits\Reponser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentStagesController extends BaseController
{


    public function index()
    {
        $this->authorize('shipmentstages.index');
        $status = SystemStatus::SchemaSCHIndex();
        $turns = StorageTurn::turnActiveToSelect();
        return view('Shipments.ShipmentStages.index', compact('status', 'turns'));
    }

    public function getshipmentstage(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->ForStatus($request->get('statusid'))
            ->Shipmentid($request->get('shipmentid'))
            ->Workday($request->get('workday'))
            ->Turn($request->get('turnid'))
            ->leftjoin("storageturns as s", "orderdeliveries.turnid", "=", "s.id")
            ->leftjoin("systemstatus as s2", "orderdeliveries.statusid", "=", "s2.statusid")
            ->leftjoin("shipmentheader as s3", "orderdeliveries.id", "=", "s3.orderid")
            ->leftjoin("storagelocations as s4", "s3.locationid", "=", "s4.id")
            ->leftjoin("ordercustomers as o", "orderdeliveries.customerid", "=", "o.id")
            ->leftJoin('shipmentstages as sh', 'orderdeliveries.id', '=', 'sh.orderid')
            ->leftJoin('storagelocations as sl1', 'sh.value01', '=', 'sl1.id')
            ->leftJoin('storagelocations as sl2', 'sh.value02', '=', 'sl2.id')
            ->leftJoin('storagelocations as sl3', 'sh.value03', '=', 'sl3.id')
            ->whereNotIn("orderdeliveries.statusid", ['CA', 'EJ', 'DH', 'DO', 'FO', 'IP'])
            ->select(["orderdeliveries.warehouseid", "orderdeliveries.id",
                "orderdeliveries.workday", "s.name as turno", "orderdeliveries.shipmentid",
                "s2.name as statusname", "s4.name as precarga",
                "o.name as customer", 's2.statusid', 'sl1.name as cortina01', 'sl2.name as cortina02', 'sl3.name as cortina03'])
            ->get();


        return DataTables::of($data)
            ->addColumn('addstage', 'Shipments.ShipmentStages.partials.buttons')
            ->rawColumns(['addstage'])
            ->toJson();
    }

    public function editstage($id)
    {
        $locations = StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->where('storagezones.zoneid', '=', 'CO')
            ->select('storagelocations.name', 'storagelocations.id')->get();
        $orderdeliverie = OrderDeliverie::findOrFail($id);
        $stages = ShipmentStage::leftJoin('storagelocations as s1', 'shipmentstages.value01', '=', 's1.id')
            ->leftJoin('storagelocations as s2', 'shipmentstages.value02', '=', 's2.id')
            ->leftJoin('storagelocations as s3', 'shipmentstages.value03', '=', 's3.id')
            ->select(['s1.name as cortina01', 's2.name as cortina02', 's3.name as cortina03'])
            ->where('shipmentstages.orderid', $orderdeliverie->id)->first();

        return view('Shipments.ShipmentStages.edit', compact('orderdeliverie', 'locations', 'stages'));
    }

    public function update($id, Request $request)
    {
        $order = OrderDeliverie::findOrFail($id);
        ShipmentStage::updateOrCreate([
            'orderid' => $order->id,
        ], [
            'value01' => $request['locationid01'],
            'value02' => $request['locationid02'],
            'value03' => $request['locationid03']
        ]);
        return $this->redirectBackSuccess();
    }


}
