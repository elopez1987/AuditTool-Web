<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Repositories\Receipt\ReceiptAsnShowRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class ReceiptAsnShowService
{
    public function __construct(private ReceiptAsnShowRepository $repository) {}

    /**
     * @return array{
     *     receipt: ReceiptAsn,
     *     stocks: Collection<int, ReceiptStock>,
     *     comments: Collection<int, StorageComment>,
     *     evidences: Collection<int, EvidencePhoto>,
     *     incidences: Collection<int, ReceiptIncidenceStock>
     * }|null
     */
    public function details(string $uuid): ?array
    {
        $receipt = $this->repository->findVisibleByUuid($uuid);

        if ($receipt === null) {
            return null;
        }

        $receiptId = (int) $receipt->id;

        return [
            'receipt' => $receipt,
            'stocks' => $this->stocks($receiptId),
            'comments' => $this->repository->comments($receiptId),
            'evidences' => $this->repository->evidences($receiptId),
            'incidences' => $this->repository->incidences($receiptId),
        ];
    }

    /** @return Collection<int, ReceiptStock> */
    public function stocks(int $receiptId): Collection
    {
        return $this->repository->stocks($receiptId);
    }
}
