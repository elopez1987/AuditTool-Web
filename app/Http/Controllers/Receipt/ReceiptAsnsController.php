<?php

declare(strict_types=1);

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptAsnRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Services\Receipt\ReceiptAsnShowService;
use App\ViewModels\Receipt\ReceiptAsnShowViewModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Yajra\DataTables\Facades\DataTables;

class ReceiptAsnsController extends BaseController
{
    public function __construct(
        private readonly ReceiptAsnShowService $showService,
        private readonly ReceiptAsnShowViewModel $showViewModel,
    ) {}

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
                'warehouse:warehouseid,name', 'provider:id,providerid,name', 'operator:id,name',
            ])
            ->select([
                'receiptasn.id',
                'receiptasn.uuid_asn',
                'receiptasn.centerid',
                'receiptasn.warehouseid',
                'receiptasn.shipmentid',
                'receiptasn.providerid',
                'receiptasn.addasndate',
                'receiptasn.goodissuedate',
                'receiptasn.cancellationdate',
                'receiptasn.statusid',
                'receiptasn.operatorid',
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
        $operators = $this->getOperators();

        return view('Receipt.ReceiptAsn.create', compact(
            'centers',
            'warehouses',
            'providers',
            'operators',
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

    public function edit($id)
    {
        $this->authorize('receiptasn.edit');
        $receiptasn = ReceiptAsn::controlAccessUser()->where('uuid_asn', $id)->first();

        if (empty($receiptasn)) {
            return $this->errorResponse('No existe el número de recibo solicitado.');
        }

        $providers = $this->getReceiptProviderForSelect();

        return view('Receipt.ReceiptAsn.edit', compact(
            'receiptasn',
            'providers',
        ));
    }

    public function update(Request $request, ReceiptAsn $receiptasn)
    {
        $receiptasn->update($request->all());

        return $this->infoResponse('schedulereceipt.index');
    }

    public function show(string $id): View|RedirectResponse
    {
        $details = $this->showService->details($id);
        if ($details === null) {
            return $this->errorResponse('No se encuentra el número de recibo solicitado.');
        }

        return view('Receipt.ReceiptAsn.show', $this->showViewModel->toArray($details));
    }

    public function destroy(ReceiptAsn $receiptasn)
    {
        $receiptasn->delete();

        return $this->successResponse('receiptasn.index');
    }

    /** @return Collection<int, ReceiptStock> */
    public function getTotalsReceipt(int $receiptid): Collection
    {
        return $this->showService->stocks($receiptid);
    }
}
