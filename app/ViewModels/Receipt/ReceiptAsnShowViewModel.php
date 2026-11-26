<?php

declare(strict_types=1);

namespace App\ViewModels\Receipt;

use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptIncidenceStock;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptVerification;
use Illuminate\Database\Eloquent\Collection;

final class ReceiptAsnShowViewModel
{
    /**
     * @param array{
     *     receipt: ReceiptAsn,
     *     stocks: Collection<int, ReceiptStock>,
     *     comments: Collection<int, StorageComment>,
     *     evidences: Collection<int, EvidencePhoto>,
     *     definitiveVerificationEvidences: Collection<int, ReceiptVerification>,
     *     incidences: Collection<int, ReceiptIncidenceStock>
     * } $details
     * @param  array<string, mixed>  $receiptData
     * @return array<string, mixed>
     */
    public function toArray(array $details, array $receiptData = []): array
    {
        foreach ($details['stocks'] as $stock) {
            $stock->setAttribute('quantitytrue', $stock->quantity);
        }

        /** @var list<array<string, mixed>> $expectedLines */
        $expectedLines = $receiptData['ExpectedLines'] ?? [];
        $expectedLinesByKey = [];
        foreach ($expectedLines as $line) {
            $expectedLinesByKey[$this->lineKey($line)] = $line;
        }

        /** @var list<array<string, mixed>> $products */
        $products = $receiptData['Products'] ?? [];
        $expectedProducts = [];
        foreach ($products as $product) {
            $line = $expectedLinesByKey[$this->lineKey($product)] ?? [];
            $expectedProducts[] = [
                ...$product,
                'UoMBase' => $line['UoMBase'] ?? '',
                'ExpectedTotal' => $line['ExpectedTotal'] ?? null,
                'ExpectedRotary' => $line['ExpectedRotary'] ?? null,
                'ExpectedDefinitive' => $line['ExpectedDefinitive'] ?? null,
            ];
        }

        return [
            'receiptasn' => $details['receipt'],
            'stocks' => $details['stocks'],
            'coments' => $details['comments'],
            'incidences' => $details['incidences'],
            'evidences' => $details['evidences'],
            'definitiveVerificationEvidences' => $details['definitiveVerificationEvidences'],
            'expectedProducts' => $expectedProducts,
            'verificationLines' => $expectedLines,
        ];
    }

    /** @param array<string, mixed> $line */
    private function lineKey(array $line): string
    {
        return sprintf('%s:%s', $line['Source'] ?? '', $line['ExpectedLineId'] ?? '');
    }
}
