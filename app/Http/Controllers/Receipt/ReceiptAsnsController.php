<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptAsnRequest;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Yard\StorageReason;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReceiptAsnsController extends BaseController
{
    public function index()
    {
        $this->authorize('receiptasn.index');
        $status = $this->getStatusForSchema('SRE');

        return view('Receipt.ReceiptAsn.index', compact('status'));
    }

    public function getDataAsns(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()
            ->with(['status', 'center', 'warehouse', 'carrier', 'provider', 'turn', 'reason', 'inventorystock'])
            ->Name($request->get('name'))
            ->Shipment($request->get('shipmentid'))
            ->FolioNumber($request->get('folionumber'))
            ->OC($request->get('oc'))
            ->Status($request->get('statusid'))
            ->select('receiptasn.*')->get();

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
        $turns = $this->getTurns();
        $carriers = $this->getCarriersForSelect();
        $categorystocks = InventoryStockCategory::select('description', 'id')
            ->where('statusid', 'E')->get();
        $reasons = StorageReason::whereType('RE')->select('name', 'id')->get();

        return view('Receipt.ReceiptAsn.create', compact(
            'centers',
            'warehouses',
            'providers',
            'reasons',
            'categorystocks',
            'turns',
            'carriers'
        ));
    }

    public function store(CreateReceiptAsnRequest $request)
    {
        $receipt = new ReceiptAsn($request->validated());
        $receipt->statusid = 'RE';
        $receipt->workday = Carbon::parse($request['addasndate'])->format('Y-m-d');
        $receipt->auxiliary01 = Auth::user()->name;
        $receipt->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro de recibo exitoso,Recibo :' . $receipt->shipmentid,
            ], 200);
        }

        return $this->successResponse('receiptasn.index');

    }

    public function edit(ReceiptAsn $receiptasn)
    {
        $this->authorize('receiptasn.edit');
        $carriers = $this->getCarriersForSelect();
        $providers = $this->getReceiptProviderForSelect();
        $turns = StorageTurn::select('name', 'id')->get();
        $categorystocks = InventoryStockCategory::select('description', 'id')
            ->where('statusid', 'E')
            ->get();
        $reasons = StorageReason::whereType('RE')->select('name', 'id')->get();

        return view('Receipt.ReceiptAsn.edit', compact(
            'receiptasn',
            'providers',
            'carriers',
            'reasons',
            'categorystocks',
            'turns'
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
        $totalContainers = ReceiptContainer::getContainersForReceipt($receiptasn->id);

        return view(
            'Receipt.ReceiptAsn.show',
            compact('receiptasn', 'stocks', 'coments', 'evidences', 'incidences', 'totalContainers')
        );
    }

    public function destroy(ReceiptAsn $receiptasn)
    {
        $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()->where('receiptid', $receiptasn->id)->first();
        if (! empty($schedulleReceipt)) {
            $schedulleReceipt->receiptid = null;
            $schedulleReceipt->save();
        }
        $receiptasn->delete();

        return $this->successResponse('schedulereceipt.index');
    }

    public function getTotalsReceipt($receiptid)
    {
        return ReceiptStock::controlAccessUser()->select(
            'inventorypartnumbers.prtnum',
            'inventorypartnumbers.lngdsc',
            'receiptstock.expirecode',
            'receiptstock.quantitytrue',
            'inventoryunitsmeasure.description as unidadmedida',
            DB::raw('(SUM(receiptcontainers.quantity)) as totalescaneado')
        )
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('receiptstock.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('receiptstock.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('receiptcontainers', function ($join) {
                $join->on('receiptstock.receiptid', '=', 'receiptcontainers.receiptid')
                    ->on('receiptstock.centerid', '=', 'receiptcontainers.centerid')
                    ->on('receiptstock.warehouseid', '=', 'receiptcontainers.warehouseid')
                    ->on('receiptstock.partnumberid', '=', 'receiptcontainers.partnumberid');
                //                    ->on('receiptstock.expirecode', '=', 'receiptcontainers.batch');
            })
            ->leftJoin('inventoryunitsmeasure', 'receiptstock.unitmeasureid', '=', 'inventoryunitsmeasure.id')
            ->where('receiptstock.receiptid', '=', $receiptid)
            ->groupBy(
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'receiptstock.expirecode',
                'receiptstock.quantitytrue',
                'inventorypartnumbers.untcas',
                'inventoryunitsmeasure.description'
            )
            ->get();
    }
}
