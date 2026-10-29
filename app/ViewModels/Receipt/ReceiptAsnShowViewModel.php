<?php

declare(strict_types=1);

namespace App\ViewModels\Receipt;

use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use Illuminate\Database\Eloquent\Collection;

final class ReceiptAsnShowViewModel
{
    /**
     * @param array{
     *     receipt: ReceiptAsn,
     *     stocks: Collection<int, ReceiptStock>,
     *     comments: Collection<int, StorageComment>,
     *     evidences: Collection<int, EvidencePhoto>,
     *     incidences: Collection<int, ReceiptIncidenceStock>
     * } $details
     * @return array<string, mixed>
     */
    public function toArray(array $details): array
    {
        foreach ($details['stocks'] as $stock) {
            $stock->setAttribute('quantitytrue', $stock->quantity);
        }

        return [
            'receiptasn' => $details['receipt'],
            'stocks' => $details['stocks'],
            'coments' => $details['comments'],
            'incidences' => $details['incidences'],
            'evidences' => $details['evidences'],
        ];
    }
}
