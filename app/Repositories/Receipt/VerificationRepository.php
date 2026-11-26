<?php

declare(strict_types=1);

namespace App\Repositories\Receipt;

use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptStockCredit;
use App\Models\Receipt\ReceiptVerification;
use App\Services\Receipt\ExpectedReceiptLine;
use App\Services\Receipt\VerificationSource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\LazyCollection;

final class VerificationRepository
{
    public function lockAsn(string $uuid, string $username): ?ReceiptAsn
    {
        return $this->accessibleAsn($username)->where('uuid_asn', $uuid)->lockForUpdate()->first();
    }

    public function updateCreditBalances(
        int $creditId,
        string $quantityMissing,
        string $rotary,
        string $definitive,
    ): void {
        ReceiptStockCredit::query()
            ->whereKey($creditId)
            ->update([
                'quantity_missing' => $quantityMissing,
                'rotary' => $rotary,
                'definitive' => $definitive,
            ]);
    }

    public function lockAccessibleReceiptId(int $id, string $username): ?ReceiptAsn
    {
        return $this->accessibleAsn($username)->whereKey($id)->lockForUpdate()->first();
    }

    /** @return Builder<ReceiptAsn> */
    private function accessibleAsn(string $username): Builder
    {
        return ReceiptAsn::query()
            ->whereExists(function (QueryBuilder $query) use ($username): void {
                $query->selectRaw('1')->from('securityaccesswarehouses')
                    ->where('username', $username)
                    ->whereColumn('securityaccesswarehouses.centerid', 'receiptasn.centerid')
                    ->whereColumn('securityaccesswarehouses.warehouseid', 'receiptasn.warehouseid');
            });
    }

    public function hasAnyVerifications(int $receiptId): bool
    {
        return ReceiptVerification::query()->where('receiptasn_id', $receiptId)->exists();
    }

    /** @return LazyCollection<int, ReceiptAsn> */
    public function historicalReceipts(?string $uuid): LazyCollection
    {
        return ReceiptAsn::query()
            ->when($uuid !== null, fn (Builder $query) => $query->where('uuid_asn', $uuid))
            ->whereHas('verifications', fn (Builder $query) => $query->whereNull('receiptstock_id')->whereNull('receipt_stock_credit_id'))
            ->lazyById(100);
    }

    public function lockReceiptId(int $id): ?ReceiptAsn
    {
        return ReceiptAsn::query()->whereKey($id)->lockForUpdate()->first();
    }

    /** @return Collection<int, ReceiptVerification> */
    public function historicalVerifications(int $receiptId): Collection
    {
        return ReceiptVerification::query()->where('receiptasn_id', $receiptId)
            ->whereNull('receiptstock_id')->whereNull('receipt_stock_credit_id')
            ->orderBy('id')->lockForUpdate()->get();
    }

    public function linkHistoricalVerification(ReceiptVerification $verification, ExpectedReceiptLine $line): void
    {
        $verification->update([$line->source->foreignKey() => $line->id]);
    }

    public function operation(int $receiptId, string $operationId): ?ReceiptVerification
    {
        return ReceiptVerification::query()
            ->where('receiptasn_id', $receiptId)
            ->where('client_operation_id', $operationId)
            ->first();
    }

    /** @return list<ExpectedReceiptLine> */
    public function lines(int $receiptId, bool $lock = false): array
    {
        $result = [];

        foreach (VerificationSource::cases() as $source) {
            $stock = $source === VerificationSource::StockRoute;
            $query = $stock ? ReceiptStock::query() : ReceiptStockCredit::query();
            $query->where($stock ? 'receiptid' : 'receiptasn_id', $receiptId)
                ->with($stock ? ['partnumber', 'unitmeasure'] : ['inventorypartnumber', 'inventoryunitsmeasure'])
                ->orderBy('id');

            if ($lock) {
                $query->lockForUpdate();
            }

            foreach ($query->get() as $line) {
                $product = $line->getRelation($stock ? 'partnumber' : 'inventorypartnumber');
                $unit = $line->getRelation($stock ? 'unitmeasure' : 'inventoryunitsmeasure');
                $result[] = new ExpectedReceiptLine(
                    $source,
                    (int) $line->id,
                    $receiptId,
                    (int) $line->getAttribute($stock ? 'partnumberid' : 'inventorypartnumber_id'),
                    $unit === null ? null : (int) $unit->id,
                    $this->rawQuantity($line->getRawOriginal('quantity')),
                    $this->rawQuantity($line->getRawOriginal('rotary')),
                    $this->rawQuantity($line->getRawOriginal('definitive')),
                    $stock ? null : $this->rawQuantity($line->getRawOriginal('quantity_missing')),
                    (string) $product?->prtnum,
                    $unit?->unitmeasureid,
                    $product?->lngdsc,
                    $product?->barcode,
                );
            }
        }

        return $result;
    }

    /** @return array<string, array<string, string>> */
    public function balances(int $receiptId): array
    {
        $balances = [];

        foreach (VerificationSource::cases() as $source) {
            $column = $source->foreignKey();
            $totals = ReceiptVerification::query()
                ->where('receiptasn_id', $receiptId)->whereNotNull($column)
                ->select([$column, 'type_id'])->selectRaw('SUM(quantity) as verified_quantity')
                ->groupBy($column, 'type_id')->get();

            foreach ($totals as $total) {
                $balances[$source->value.':'.$total->getAttribute($column)][$total->type_id] = (string) $total->getAttribute('verified_quantity');
            }
        }

        return $balances;
    }

    public function hasUnlinkedVerifications(int $receiptId, ?int $productId = null): bool
    {
        return ReceiptVerification::query()->where('receiptasn_id', $receiptId)
            ->when($productId !== null, fn (Builder $query) => $query->where('inventorypartnumber_id', $productId))
            ->whereNull('receiptstock_id')->whereNull('receipt_stock_credit_id')->exists();
    }

    /** @return list<int> */
    public function unlinkedProductIds(int $receiptId): array
    {
        return ReceiptVerification::query()->where('receiptasn_id', $receiptId)
            ->whereNull('receiptstock_id')->whereNull('receipt_stock_credit_id')
            ->distinct()->pluck('inventorypartnumber_id')->map(fn (mixed $id): int => (int) $id)->all();
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): ReceiptVerification
    {
        return ReceiptVerification::query()->create($attributes);
    }

    public function recordShortageConfirmation(int $receiptId, int $userId, string $reason): void
    {
        StorageComment::query()->create([
            'modeltype' => 'ReceiptAsn',
            'modelid' => $receiptId,
            'createuser' => $userId,
            'comment' => $reason,
            'type' => 'VERIFICATION_SHORTAGE',
        ]);
    }

    private function rawQuantity(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }
}
