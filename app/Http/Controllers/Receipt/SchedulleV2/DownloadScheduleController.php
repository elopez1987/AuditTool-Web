<?php

namespace App\Http\Controllers\Receipt\SchedulleV2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptAsnRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Yard\StorageReason;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DownloadScheduleController extends BaseController
{
    public function index()
    {
        $status = $this->getStatusForSchema('SRE');
        return view('Receipt.SchedulleV2.index', compact('status'));
    }

    public function getDataSchedulleV2(Request $request)
    {
        $data = StorageSchedulleReceipt::controlAccessUser()
            ->Workday($request->get('workday'))
            ->Shipment($request->get('shipmentid'))
            ->select([
                'storageschedullereceipt.centerid',
                'storageschedullereceipt.warehouseid',
                'storageschedullereceipt.statusid',
                'sc.description as centername',
                'sw.name as warehousename',
                'storageschedullereceipt.id',
                'storageschedullereceipt.periodid',
                'storageschedullereceipt.workday',
                's1.name as secuencia',
                'ra.shipmentid',
                'r2.name as proveedor',
                's3.name as carriername',
                'ra.oc',
                'ra.billoflading as factura',
                'ra.auxiliary02 as po',
                'ra.partnumberid as material',
                'ra.quantity',
                'ra.downloadpriority as priority',
                'ra.comment',
                'i.description as tipo',
                's2.name as status',
                's2.auxiliary01 as color',
                'sl1.name as cortina',
                'ra.id as receiptid',
                'ra.downloadpriority',
                'ra.goodissuedate as goodissuedate',
                'ra.statusid',
                'ra.po as grsap'
            ])
            ->leftJoin('receiptasn as ra', function ($join) {
                $join->on('storageschedullereceipt.receiptid', '=', 'ra.id')
                    ->on('storageschedullereceipt.centerid', '=', 'ra.centerid')
                    ->on('storageschedullereceipt.warehouseid', '=', 'ra.warehouseid');
            })
            ->leftJoin('receiptproviders as r2', function ($join) {
                $join->on('ra.providerid', '=', 'r2.id')
                    ->on('ra.centerid', '=', 'r2.centerid')
                    ->on('ra.warehouseid', '=', 'r2.warehouseid');
            })
            ->leftJoin('shipmentcarriers as s3', function ($join) {
                $join->on('ra.carrierid', '=', 's3.id')
                    ->on('ra.centerid', '=', 's3.centerid');
            })
            ->leftJoin('receiptunits as ru', function ($join){
                $join->on('ra.folionumber','=','ru.id')
                    ->on('ra.centerid','=','ru.centerid')
                    ->on('ra.warehouseid','=','ru.warehouseid');
            })->leftJoin('storagelocations as sl1', function ($join) {
                $join->on('ru.locationid', '=', 'sl1.id')
                    ->on('ru.centerid', '=', 'sl1.centerid');
            })
            ->leftJoin('inventorystockcategories as i', 'ra.stockcategoryid', '=', 'i.id')
            ->leftJoin('systemstatus as s1', 'storageschedullereceipt.secuence', '=', 's1.statusid')
            ->leftJoin('systemstatus as s2', 'ra.statusid', '=', 's2.statusid')
            ->leftJoin('systemcenterdistributions as sc', 'storageschedullereceipt.centerid', '=', 'sc.centerid')
            ->leftJoin('systemwarehouses as sw', 'storageschedullereceipt.warehouseid', '=', 'sw.warehouseid')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Receipt.SchedulleV2.partials.buttons')
            ->addColumn('prioridad', 'Receipt.SchedulleV2.addpriority')
            ->addColumn('po', 'Receipt.SchedulleV2.goodissuedate')
            ->rawColumns(['btn','prioridad','po'])
            ->toJson();
    }

    public function addShipmentToSchedule($id)
    {
        $this->authorize('receiptasn.create');
        $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()->findOrFail($id);
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $providers = $this->getReceiptProviderForSelect();
        $turns = $this->getTurns();
        $carriers = $this->getCarriersForSelect();
        $categorystocks = InventoryStockCategory::select('description', 'id')->get();
        $reasons = StorageReason::whereType('RE')->select('name', 'id')->get();
        return view('Receipt.SchedulleV2.edit',
            compact('schedulleReceipt', 'carriers', 'providers', 'reasons', 'categorystocks', 'turns', 'centers', 'warehouses'));

    }

    public function addShipmentToScheduleedit(CreateReceiptAsnRequest $request, $id)
    {
        $this->authorize('receiptasn.create');
        DB::beginTransaction();
        try {
            $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()->findOrFail($id);
            $hour = SystemStatus::where('statusid', $schedulleReceipt->secuence)->first();
            $combinedDateTime = $schedulleReceipt->workday . ' ' . $hour->auxiliary04;
            $formattedDateTime = Carbon::createFromFormat('Y-m-d H:i', $combinedDateTime);

            $receipt = new ReceiptAsn($request->validated());
            $receipt->statusid = 'PG';
            $receipt->addasndate = $formattedDateTime;
            $receipt->workday = Carbon::parse($schedulleReceipt->workday)->format('Y-m-d');
            $receipt->auxiliary01 = Auth::user()->name;  //Planeador del recibo
            $receipt->save();

            $schedulleReceipt->receiptid = $receipt->id;
            $schedulleReceipt->statusid = 'DPR-02';
            $schedulleReceipt->save();

            DB::commit();
            return $this->redirectBackSuccessMessage('Recibo agregado al plan de entrega');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }


}
