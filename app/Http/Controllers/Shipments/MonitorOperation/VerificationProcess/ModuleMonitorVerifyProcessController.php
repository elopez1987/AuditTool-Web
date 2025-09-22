<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\ShipmentContainerCheck;
use App\Models\Shipments\ShipmentContainerMoveBox;
use App\Models\Shipments\ShipmentControl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuleMonitorVerifyProcessController extends BaseController
{
    public function index($id)
    {
        $this->authorize('monitoroperation.check');
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('El número de embarque no existe,favor de validar');
        }
        $concepts = StorageCategoryIncidenceDetail::controlAccessUser()
            ->whereAuxiliary01('CFE')->select(['description', 'categoryincdetailid'])->get();
        $lpns = ShipmentContainer::controlAccessUser()->where('orderid', '=', $id)->distinct()->get();
        $lpsFusionados = ShipmentContainerCheck::where('orderid', '=', $id)->get();
        $kardexMoveBox = ShipmentContainerMoveBox::where('orderid', '=', $id)->get();
        $checks = $this->getTotalChecks($id);
        $pedidos = ShipmentControl::controlAccessUser()->where('orderid', '=', $id)->get();
        $total = $this->getTotalGeneral($id);
        $detailLpns = $this->getDetailLpns($id);
        return view(
            'Shipments.OrderMonitor.MonitorOperation.VerificationProcess.index',
            compact('orderDelivery', 'checks', 'total', 'concepts', 'lpns', 'detailLpns', 'lpsFusionados', 'kardexMoveBox', 'pedidos')
        );
    }

    public function getDetailLpns($id): Collection
    {
        return DB::table('shipmentstock')
            ->select(
                'inventorypartnumbers.prtnum as partnumberid',
                'inventorypartnumbers.lngdsc as description',
                DB::raw('sum(shipmentstock.quantity) as total'),
                'shipmentstock.lpnid',
                'users.name as createuser'
            )
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('shipmentstock.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('inventorypartnumbers.centerid', '=', 'shipmentstock.centerid');
            })
            ->join('users', 'shipmentstock.createuser', '=', 'users.username')
            ->where('shipmentstock.orderid', '=', $id)
            ->groupBy('inventorypartnumbers.prtnum', 'inventorypartnumbers.lngdsc', 'shipmentstock.lpnid', 'users.name')
            ->get();
    }

    public function getTotalChecks($id): Collection
    {
        return DB::table('shipmentcontrol')
            ->select(
                'shipmentcontrol.recordid',
                'orderdeliveries.shipmentid',
                'inventorypartnumbers.prtnum as sku',
                'inventorypartnumbers.lngdsc as description',
                'inventoryunitsmeasure.description as unitmeasureid',
                'shipmentcontrol.missingconceptid',
                'shipmentcontrol.quantityrequested as requested',
                'storagecategoryincidencedetails.description as concept',
                DB::raw('sum(shipmentstock.quantity) as totalver')
            )
            ->addSelect(DB::raw('sum(shipmentstock.quantity) - shipmentcontrol.quantityrequested as quantityfail'))
            ->join('orderdeliveries', function ($join) {
                $join->on('shipmentcontrol.orderid', '=', 'orderdeliveries.id')
                    ->on('shipmentcontrol.centerid', '=', 'orderdeliveries.centerid');
            })
            ->leftJoin('shipmentstock', function ($join) {
                $join->on('shipmentcontrol.orderid', '=', 'shipmentstock.orderid')
                    ->on('shipmentcontrol.centerid', '=', 'shipmentstock.centerid')
                    ->on('shipmentcontrol.partnumberid', '=', 'shipmentstock.partnumberid');
            })
            ->join('inventorypartnumbers', function ($join) {
                $join->on('shipmentcontrol.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('shipmentcontrol.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->join('inventoryunitsmeasure', function ($join) {
                $join->on('inventorypartnumbers.stkuom', '=', 'inventoryunitsmeasure.unitmeasureid')
                    ->on('inventorypartnumbers.centerid', '=', 'inventoryunitsmeasure.centerid');
            })
            ->leftJoin('storagecategoryincidencedetails', 'shipmentcontrol.missingconceptid', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->where('shipmentcontrol.orderid', '=', $id)
            ->groupBy(
                'shipmentcontrol.recordid',
                'orderdeliveries.shipmentid',
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'shipmentcontrol.missingconceptid',
                'inventoryunitsmeasure.description',
                'shipmentcontrol.quantityrequested',
                'storagecategoryincidencedetails.description'
            )
            ->get();
    }

    public function getTotalGeneral($id)
    {
        return ShipmentControl::controlAccessUser()->select([
            DB::raw('sum(shipmentcontrol.quantityrequested) as totalpedido'),
            DB::raw('(select sum(quantity) from shipmentstock where shipmentstock.orderid = shipmentcontrol.orderid) as verificado'),
        ])->where('shipmentcontrol.orderid', $id)
            ->groupBy('shipmentcontrol.orderid')->first();
    }
}
