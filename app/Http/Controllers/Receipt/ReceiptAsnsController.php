<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptAsnRequest;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Models\Yard\StorageReason;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Yajra\DataTables\Facades\DataTables;

class ReceiptAsnsController extends BaseController
{
    public function index()
    {
        $this->authorize('receiptasn.index');
        $status = $this->getStatusForSchema('SRE');
        $warehouses = $this->getWarehouseForSelect();

        return view('Receipt.ReceiptAsn.index', compact('status', 'warehouses'));
    }

    public function getDataAsns(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()
            ->with(['status:statusid,name', 'center:centerid,name,description',
                'warehouse:warehouseid,name', 'provider:id,providerid,name',
                'reason:id,name', 'inventorystock:id,description',
            ])
            ->select([
                'receiptasn.id',
                'receiptasn.centerid',
                'receiptasn.warehouseid',
                'receiptasn.folionumber',
                'receiptasn.shipmentid',
                'receiptasn.billoflading',
                'receiptasn.providerid',
                'receiptasn.razonstatusid',
                'receiptasn.stockcategoryid',
                'receiptasn.addasndate',
                'receiptasn.goodissuedate',
                'receiptasn.cancellationdate',
                'receiptasn.statusid',
            ]);

        return DataTables::of($data)
            ->addColumn('btn', 'Receipt.ReceiptAsn.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('receiptasn.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $providers = $this->getReceiptProviderForSelect();
        $categorystocks = InventoryStockCategory::controlAccessUser()
            ->select('description', 'id')->where('statusid', 'E')->get();
        $reasons = StorageReason::select('id', 'name')->whereRaw("charindex('RES-02', configurationflag) > 0")->get();

        return view('Receipt.ReceiptAsn.create', compact(
            'centers',
            'warehouses',
            'providers',
            'reasons',
            'categorystocks',
        ));
    }

    public function store(CreateReceiptAsnRequest $request)
    {
        try {

            $receipt = ReceiptAsn::create([
                ...$request->validated(),
                'statusid' => 'PG',
                'workday' => Carbon::parse($request->validated('addasndate'))->format('Y-m-d'),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Registro de recibo exitoso, Recibo: {$receipt->shipmentid}",
                ], 201); // Cambiar a 201 para creación exitosa
            }

        } catch (Exception $e) {
            Log::error('Error creating receipt: '.$e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el recibo.',
                ], 500);
            }
        }
    }

    public function edit(ReceiptAsn $receiptasn)
    {
        $this->authorize('receiptasn.edit');
        $providers = $this->getReceiptProviderForSelect();
        $categorystocks = InventoryStockCategory::select('description', 'id')
            ->where('statusid', 'E')->get();
        $reasons = StorageReason::select('id', 'name')->whereRaw("charindex('RES-02', configurationflag) > 0")->get();

        return view('Receipt.ReceiptAsn.edit', compact(
            'receiptasn',
            'providers',
            'reasons',
            'categorystocks',
        ));
    }

    public function update(Request $request, ReceiptAsn $receiptasn)
    {
        $receiptasn->update($request->all());

        return $this->infoResponse('schedulereceipt.index');
    }

    public function show(ReceiptAsn $receiptasn)
    {
        $stocks = $this->getTotalsReceipt($receiptasn->id);
        $coments = StorageComment::getCommentForModel($receiptasn->id, 'ReceiptAsn');
        $evidences = EvidencePhoto::getPhotosForModel($receiptasn->id, 'ReceiptAsn');
        $incidences = ReceiptIncidenceStock::with(['partnumber', 'categoryincidence', 'subcategoryincidence'])
            ->where('receiptid', $receiptasn->id)->get();

        return view(
            'Receipt.ReceiptAsn.show',
            compact('receiptasn', 'stocks', 'coments',
                'evidences', 'incidences'),
        );
    }

    public function destroy(ReceiptAsn $receiptasn)
    {
        $receiptasn->delete();

        return $this->successResponse('receiptasn.index');
    }

    public function getTotalsReceipt($receiptid)
    {
        return ReceiptStock::select(
            'inventorypartnumbers.prtnum',
            'inventorypartnumbers.lngdsc',
            'receiptstock.id',
            'receiptstock.expirecode',
            'receiptstock.quantity',
            'inventoryunitsmeasure.description as unidadmedida',
            DB::raw('(SUM(receiptcontainers.quantity)) as totalescaneado'),
        )
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('receiptstock.partnumberid', '=', 'inventorypartnumbers.id');
            })
            ->leftJoin('receiptcontainers', function ($join) {
                $join->on('receiptstock.receiptid', '=', 'receiptcontainers.receiptid')
                    ->on('receiptstock.partnumberid', '=', 'receiptcontainers.partnumberid');
            })
            ->leftJoin('inventoryunitsmeasure', 'receiptstock.unitmeasureid', '=', 'inventoryunitsmeasure.id')
            ->where('receiptstock.receiptid', '=', $receiptid)
            ->groupBy(
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'receiptstock.id',
                'receiptstock.expirecode',
                'receiptstock.quantity',
                'inventorypartnumbers.untcas',
                'inventoryunitsmeasure.description',
            )
            ->get();
    }
}
