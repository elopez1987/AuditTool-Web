<?php

declare(strict_types=1);

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptAsn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PanelController extends BaseController
{
    public function index(): View
    {
        $operators = $this->getOperators();
        $providers = $this->getReceiptProviderForSelect();

        return view('Receipt.Panel.index', compact('operators', 'providers'));
    }

    public function getReceiptProcesses(Request $request): JsonResponse
    {
        $receiptProcesses = $this->getReceiptProcess($request)
            ->orderBy('receiptasn.addasndate')
            ->get()
            ->map(function (ReceiptAsn $receiptProcess): array {
                return [
                    'shipmentid' => $receiptProcess->shipmentid,
                    'status' => $receiptProcess->status?->name ?? $receiptProcess->statusid,
                    'warehouse' => $receiptProcess->warehouse,
                    'provider' => $receiptProcess->providername,
                    'operator' => $receiptProcess->operatorname,
                    'quantity_to_receive' => (float) ($receiptProcess->quantity_to_receive ?? 0),
                    'quantity_verified' => (float) ($receiptProcess->quantity_verified ?? 0),
                ];
            });

        return response()->json(['data' => $receiptProcesses]);
    }

    /** @return Builder<ReceiptAsn> */
    private function getReceiptProcess(Request $request): Builder
    {
        return ReceiptAsn::controlAccessUser()->with(['status', 'provider'])
            ->withSum('stockplan as quantity_to_receive', 'quantity')
            ->withSum('verifications as quantity_verified', 'quantity')
            ->leftjoin('systemwarehouses', 'receiptasn.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('receiptasndata', 'receiptasn.id', 'receiptasndata.receiptid')
            ->leftJoin('storagelocations', 'receiptasndata.locationid', 'storagelocations.id')
            ->leftJoin('storageoperators', 'receiptasn.operatorid', 'storageoperators.id')
            ->whereIn('receiptasn.statusid', ['IP', 'PR'])
            ->when($request->filled('shipmentid'), function ($query) use ($request) {
                $query->where('receiptasn.shipmentid', $request->shipmentid);
            })
            ->when($request->filled('warehouseid'), function ($query) use ($request) {
                $query->where('receiptasn.warehouseid', $request->warehouseid);
            })
            ->when($request->filled('statusid'), function ($query) use ($request) {
                $query->where('receiptasn.statusid', $request->statusid);
            })
            ->when($request->filled('operatorid'), function ($query) use ($request) {
                $query->where('receiptasn.operatorid', $request->operatorid);
            })
            ->when($request->filled('providerid'), function ($query) use ($request) {
                $query->where('receiptasn.providerid', $request->providerid);
            })
            ->select([
                'receiptasn.id', 'receiptasn.workday', 'receiptasn.shipmentid', 'receiptasn.addasndate',
                'receiptasn.billoflading', 'receiptproviders.name as providername', 'receiptproviders.providerid',
                'receiptasn.statusid', 'receiptasn.cancellationdate', 'storageoperators.name as operatorname',
                'receiptasn.goodissuedate', 'receiptasn.statusid', 'storagelocations.name as locationname',
                'systemwarehouses.name as warehouse', 'receiptasn.centerid', 'receiptasn.uuid_asn',
            ]);
    }
}
