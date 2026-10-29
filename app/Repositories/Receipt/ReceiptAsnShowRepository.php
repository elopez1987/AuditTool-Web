<?php

declare(strict_types=1);

namespace App\Repositories\Receipt;

use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

final class ReceiptAsnShowRepository
{
    public function findVisibleByUuid(string $uuid): ?ReceiptAsn
    {
        return ReceiptAsn::controlAccessUser()
            ->with([
                'center:centerid,description',
                'warehouse:warehouseid,name',
                'provider:id,name',
                'timereceipt',
            ])
            ->where('uuid_asn', $uuid)
            ->first();
    }

    /** @return Builder<ReceiptStock> */
    public function stockQuery(int $receiptId): Builder
    {
        $scanned = ReceiptContainer::query()
            ->select('receiptid', 'partnumberid')
            ->selectRaw('SUM(quantity) AS scanned_quantity')
            ->where('receiptid', $receiptId)
            ->groupBy('receiptid', 'partnumberid');

        return ReceiptStock::query()
            ->select([
                'receiptstock.id',
                'receiptstock.expirecode',
                'receiptstock.quantity',
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'inventoryunitsmeasure.description as unidadmedida',
            ])
            ->selectRaw('COALESCE(scanned.scanned_quantity, 0) AS totalescaneado')
            ->leftJoin('inventorypartnumbers', 'receiptstock.partnumberid', '=', 'inventorypartnumbers.id')
            ->leftJoin('inventoryunitsmeasure', 'receiptstock.unitmeasureid', '=', 'inventoryunitsmeasure.id')
            ->leftJoinSub($scanned, 'scanned', function (JoinClause $join): void {
                $join->on('receiptstock.receiptid', '=', 'scanned.receiptid')
                    ->on('receiptstock.partnumberid', '=', 'scanned.partnumberid');
            })
            ->where('receiptstock.receiptid', $receiptId)
            ->orderBy('receiptstock.id');
    }

    /** @return Collection<int, ReceiptStock> */
    public function stocks(int $receiptId): Collection
    {
        return $this->stockQuery($receiptId)->get();
    }

    /** @return Collection<int, StorageComment> */
    public function comments(int $receiptId): Collection
    {
        return StorageComment::query()
            ->with('user:id,name')
            ->where('modelid', $receiptId)
            ->whereIn('modeltype', ['ReceiptAsn', 'receiptasn'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    /** @return Collection<int, EvidencePhoto> */
    public function evidences(int $receiptId): Collection
    {
        return EvidencePhoto::query()
            ->with('user:id,name')
            ->where('model_id', $receiptId)
            ->where('model_type', 'ReceiptAsn')
            ->whereIn('auxiliary01', ['DES', 'INC'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    /** @return Collection<int, ReceiptIncidenceStock> */
    public function incidences(int $receiptId): Collection
    {
        return ReceiptIncidenceStock::query()
            ->with([
                'partnumber:id,prtnum,lngdsc',
                'categoryincidence:categoryincid,description',
                'subcategoryincidence:categoryincdetailid,description',
                'user:id,username,name',
            ])
            ->where('receiptid', $receiptId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }
}
