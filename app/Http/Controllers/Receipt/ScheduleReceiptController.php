<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\StorageReason;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleReceiptController extends BaseController
{
    public function index()
    {
        $this->authorize('schedulereceipt.index');
        $status = SystemStatus::whereSchemaid('SRE')->whereNotIn('statusid', ['CE', 'IG'])
            ->select('name', 'statusid')->get();
        $providers = $this->getReceiptProviderForSelect();
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $carriers = $this->getCarriersForSelect();
        $reasons = StorageReason::whereIn('id', [17, 18, 19, 20, 31, 32])->select(['name', 'id'])->get();

        return view(
            'Receipt.ScheduleReceipt.index',
            compact('status', 'providers', 'centers', 'warehouses', 'carriers', 'reasons')
        );
    }

    public function getDataReceipt(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()->with(['status', 'provider', 'folio', 'carrier', 'turn'])
            ->leftjoin('systemwarehouses', 'receiptasn.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('receiptunits', 'receiptasn.folionumber', '=', 'receiptunits.id')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('shipmentcarriers', 'receiptasn.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('inventorystockcategories', 'receiptasn.stockcategoryid', '=', 'inventorystockcategories.id')
            ->whereNotIn('receiptasn.statusid', ['CE', 'IG', 'PG'])
            ->Delivery($request->get('delivery'))
            ->Shipment($request->get('shipmentid'))
            ->Status($request->get('statusid'))
            ->Provider($request->get('providerid'))
            ->FolioNumber($request->get('folionumber'))
            ->ForWarehouse($request->get('warehouseid'))
            ->select([
                'receiptasn.id', 'receiptasn.workday', 'receiptasn.shipmentid', 'receiptasn.addasndate',
                'receiptasn.billoflading', 'receiptproviders.name as providername', 'receiptunits.containerseal',
                'inventorystockcategories.description as type', 'receiptunits.id as folionumber',
                'receiptunits.entrydate', 'shipmentcarriers.name as carriername', 'receiptasn.statusid',
                'receiptunits.boxnumber', 'storagelocations.name as locationname',
                'receiptasn.unloadstartdate', 'receiptasn.unloadfinishdate', 'receiptasn.cancellationdate',
                'receiptasn.goodissuedate', 'receiptasn.statusid', 'receiptunits.statusid as statusunit',
                'receiptunits.deliverdate', 'receiptunits.autorizaoutputdate', 'receiptunits.ensnare',
                'systemwarehouses.name as warehouse',
            ]);

        return DataTables::of($data)
            ->addColumn('btn', 'Receipt.ScheduleReceipt.partials.buttons')
            ->addColumn('sap', 'Receipt.ScheduleReceipt.partials.goodissuedate')
            ->addColumn('stays', 'Receipt.ScheduleReceipt.partials.stays')
            ->rawColumns(['btn', 'sap', 'stays', 'exit', 'arrive'])->toJson();
    }
}
