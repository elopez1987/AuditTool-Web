<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Web\Dashboard\ReceiptDashboardRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptVerification;
use App\Services\Receipt\VerificationType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DashboardController extends BaseController
{
    public function index(): View
    {
        $warehouses = $this->getWarehouseForSelect();
        $providers = $this->getReceiptProviderForSelect();
        $operators = $this->getOperators();

        return view('dashboard', compact('warehouses', 'providers', 'operators'));
    }

    public function receiptMetrics(ReceiptDashboardRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $receipts = $this->filteredReceipts($filters);
        $receiptIds = (clone $receipts)->select('receiptasn.id');

        $summary = (clone $receipts)
            ->selectRaw('COUNT(*) as total_trips')
            ->selectRaw('COUNT(DISTINCT receiptasn.providerid) as total_routes')
            ->selectRaw('COUNT(DISTINCT receiptasn.operatorid) as total_operators')
            ->first();

        $dailyReceipts = (clone $receipts)
            ->selectRaw('CAST(receiptasn.goodissuedate AS date) as received_date')
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('CAST(receiptasn.goodissuedate AS date)')
            ->orderByRaw('CAST(receiptasn.goodissuedate AS date)')
            ->get();

        $routes = (clone $receipts)
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->selectRaw("COALESCE(receiptproviders.name, 'Sin ruta') as name")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('receiptproviders.name')
            ->orderByDesc('total')
            ->get();

        $operators = (clone $receipts)
            ->leftJoin('storageoperators', 'receiptasn.operatorid', '=', 'storageoperators.id')
            ->selectRaw("COALESCE(storageoperators.name, 'Sin operador') as name")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('storageoperators.name')
            ->orderByDesc('total')
            ->get();

        $productTotals = ReceiptVerification::query()
            ->whereIn('receipt_verifications.receiptasn_id', $receiptIds)
            ->selectRaw('COALESCE(SUM(receipt_verifications.quantity), 0) as total_received')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN receipt_verifications.type_id = ? THEN receipt_verifications.quantity ELSE 0 END), 0) as total_rotary',
                [VerificationType::Rotary->value],
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN receipt_verifications.type_id = ? THEN receipt_verifications.quantity ELSE 0 END), 0) as total_definitive',
                [VerificationType::Definitive->value],
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN receipt_verifications.receipt_stock_credit_id IS NOT NULL THEN receipt_verifications.quantity ELSE 0 END), 0) as total_credit_note',
            )->first();

        $incidences = ReceiptVerification::query()
            ->leftJoin(
                'storagecategoryincidences',
                'receipt_verifications.catetory_incidence_id',
                '=',
                'storagecategoryincidences.id',
            )
            ->leftJoin(
                'inventorypartnumbers',
                'receipt_verifications.inventorypartnumber_id',
                '=',
                'inventorypartnumbers.id',
            )
            ->whereIn('receipt_verifications.receiptasn_id', (clone $receiptIds))
            ->whereNotNull('receipt_verifications.catetory_incidence_id')
            ->selectRaw("COALESCE(inventorypartnumbers.prtnum, 'Sin SKU') as sku")
            ->selectRaw("COALESCE(inventorypartnumbers.lngdsc, 'Sin descripción') as description")
            ->selectRaw("COALESCE(storagecategoryincidences.description, 'Sin categoría') as category")
            ->selectRaw('COALESCE(SUM(receipt_verifications.quantity), 0) as total')
            ->groupBy(
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'storagecategoryincidences.description',
            )
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'cards' => [
                'total_trips' => (int) ($summary?->total_trips ?? 0),
                'total_routes' => (int) ($summary?->total_routes ?? 0),
                'total_operators' => (int) ($summary?->total_operators ?? 0),
                'total_received' => (float) ($productTotals?->total_received ?? 0),
                'total_rotary' => (float) ($productTotals?->total_rotary ?? 0),
                'total_definitive' => (float) ($productTotals?->total_definitive ?? 0),
                'total_credit_note' => (float) ($productTotals?->total_credit_note ?? 0),
            ],
            'charts' => [
                'daily_receipts' => $this->dailyReceiptSeries(
                    $dailyReceipts,
                    Carbon::parse($filters['start_date']),
                    Carbon::parse($filters['end_date']),
                ),
                'routes' => $this->chartSeries($routes),
                'operators' => $this->chartSeries($operators),
                'incidences' => $this->incidenceSeries($incidences),
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<ReceiptAsn>
     */
    private function filteredReceipts(array $filters): Builder
    {
        $startDate = Carbon::parse($filters['start_date'])->startOfDay();
        $endDate = Carbon::parse($filters['end_date'])->endOfDay();

        return ReceiptAsn::controlAccessUser()
            ->where('receiptasn.statusid', 'TR')
            ->whereBetween('receiptasn.goodissuedate', [$startDate, $endDate])
            ->when($filters['warehouseid'] ?? null, function (Builder $query) use ($filters): void {
                $query->where('receiptasn.warehouseid', (string) $filters['warehouseid']);
            })
            ->when($filters['providerid'] ?? null, function (Builder $query) use ($filters): void {
                $query->where('receiptasn.providerid', (int) $filters['providerid']);
            })
            ->when($filters['operatorid'] ?? null, function (Builder $query) use ($filters): void {
                $query->where('receiptasn.operatorid', (int) $filters['operatorid']);
            });
    }

    /**
     * @param  Collection<int, object>  $dailyReceipts
     * @return array{categories: list<string>, data: list<int>}
     */
    private function dailyReceiptSeries(Collection $dailyReceipts, Carbon $startDate, Carbon $endDate): array
    {
        $totalsByDate = $dailyReceipts->mapWithKeys(
            fn (object $dailyReceipt): array => [(string) $dailyReceipt->received_date => (int) $dailyReceipt->total],
        );
        $categories = [];
        $data = [];

        foreach (CarbonPeriod::create($startDate->toDateString(), $endDate->toDateString()) as $date) {
            $dateKey = $date->toDateString();
            $categories[] = $dateKey;
            $data[] = $totalsByDate->get($dateKey, 0);
        }

        return compact('categories', 'data');
    }

    /**
     * @param  Collection<int, object>  $results
     * @return list<array{name: string, total: int|float}>
     */
    private function chartSeries(Collection $results): array
    {
        return $results->map(
            fn (object $result): array => [
                'name' => (string) $result->name,
                'total' => (float) $result->total,
            ],
        )->values()->all();
    }

    /**
     * @param  Collection<int, object>  $results
     * @return list<array{sku: string, description: string, category: string, total: float}>
     */
    private function incidenceSeries(Collection $results): array
    {
        return $results->map(
            fn (object $result): array => [
                'sku' => (string) $result->sku,
                'description' => (string) $result->description,
                'category' => (string) $result->category,
                'total' => (float) $result->total,
            ],
        )->values()->all();
    }
}
