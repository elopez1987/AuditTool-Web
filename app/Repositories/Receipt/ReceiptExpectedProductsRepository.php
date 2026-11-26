<?php

declare(strict_types=1);

namespace App\Repositories\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptStockCredit;
use App\Services\Receipt\VerificationSource;
use Illuminate\Support\Collection;

final class ReceiptExpectedProductsRepository
{
    public function stock(int $id): ?ReceiptStock
    {
        return ReceiptStock::query()->find($id);
    }

    /** @param array<string, mixed> $attributes */
    public function updateStock(ReceiptStock $stock, array $attributes): void
    {
        $stock->update($attributes);
    }

    public function deleteStock(ReceiptStock $stock): void
    {
        $stock->delete();
    }

    public function deleteAsn(ReceiptAsn $asn): void
    {
        $asn->delete();
    }

    /** @param list<string> $codes
     * @return Collection<array-key, Collection<int, InventoryPartnumber>>
     */
    public function products(ReceiptAsn $asn, array $codes): Collection
    {
        $products = InventoryPartnumber::query()->where('centerid', $asn->centerid)
            ->where('warehouseid', $asn->warehouseid)->whereIn('prtnum', $codes)
            ->get();

        return collect($products->all())->groupBy('prtnum');
    }

    /** @param list<string> $codes
     * @return Collection<array-key, Collection<int, InventoryUnitMeasure>>
     */
    public function units(ReceiptAsn $asn, array $codes): Collection
    {
        $units = InventoryUnitMeasure::query()->where('centerid', $asn->centerid)
            ->where('warehouseid', $asn->warehouseid)->whereIn('unitmeasureid', $codes)
            ->get();

        return collect($units->all())->groupBy('unitmeasureid');
    }

    /** @param list<array<string, mixed>> $rows */
    public function persist(VerificationSource $source, array $rows): void
    {
        $model = $source === VerificationSource::StockRoute ? new ReceiptStock : new ReceiptStockCredit;

        foreach ($rows as $row) {
            $stock = $source === VerificationSource::StockRoute;
            $identity = [
                $stock ? 'receiptid' : 'receiptasn_id' => $row[$stock ? 'receiptid' : 'receiptasn_id'],
                $stock ? 'partnumberid' : 'inventorypartnumber_id' => $row[$stock ? 'partnumberid' : 'inventorypartnumber_id'],
                $stock ? 'unitmeasureid' : 'inventoryunitsmeasure_id' => $row[$stock ? 'unitmeasureid' : 'inventoryunitsmeasure_id'],
            ];
            $model->newQuery()->updateOrCreate($identity, $row);
        }
    }
}
