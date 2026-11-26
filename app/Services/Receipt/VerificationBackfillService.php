<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Repositories\Receipt\VerificationRepository;
use Illuminate\Database\ConnectionInterface;

final readonly class VerificationBackfillService
{
    public function __construct(
        private VerificationRepository $repository,
        private ConnectionInterface $connection,
    ) {}

    /** @return array{eligible: int, unresolved: int, linked: int} */
    public function reconcile(?string $uuid, bool $apply): array
    {
        $result = ['eligible' => 0, 'unresolved' => 0, 'linked' => 0];

        foreach ($this->repository->historicalReceipts($uuid) as $receipt) {
            $counts = $this->connection->transaction(function () use ($receipt, $apply): array {
                $this->repository->lockReceiptId((int) $receipt->id);
                $lines = $this->repository->lines((int) $receipt->id, true);
                $balances = $this->repository->balances((int) $receipt->id);
                $counts = ['eligible' => 0, 'unresolved' => 0, 'linked' => 0];
                $candidates = [];
                $projected = [];

                foreach ($this->repository->historicalVerifications((int) $receipt->id) as $verification) {
                    $matches = array_values(array_filter($lines, fn (ExpectedReceiptLine $line): bool => $line->productId === (int) $verification->inventorypartnumber_id
                        && $line->unitId !== null
                        && $line->unitId === (int) $verification->inventoryunitsmeasure_id
                    ));

                    if (count($matches) !== 1 || VerificationType::tryFrom($verification->type_id) === null
                        || ReceiptQuantity::decimal($verification->quantity)->isLessThanOrEqualTo('0')) {
                        $counts['unresolved']++;

                        continue;
                    }

                    $line = $matches[0];
                    $candidates[$line->key()][] = [$verification, $line];
                    $projected[$line->key()] = ($projected[$line->key()] ?? ReceiptQuantity::decimal('0'))->plus($verification->quantity);
                }

                foreach ($candidates as $key => $records) {
                    $line = $records[0][1];
                    $current = ReceiptQuantity::decimal($balances[$key][VerificationType::Rotary->value] ?? '0')
                        ->plus($balances[$key][VerificationType::Definitive->value] ?? '0');

                    if ($line->quantity === null || ReceiptQuantity::decimal($line->quantity)->isNegative()
                        || $current->plus($projected[$key])->isGreaterThan($line->quantity)) {
                        $counts['unresolved'] += count($records);

                        continue;
                    }

                    foreach ($records as [$verification, $expectedLine]) {
                        $counts['eligible']++;

                        if ($apply) {
                            $this->repository->linkHistoricalVerification($verification, $expectedLine);
                            $counts['linked']++;
                        }
                    }
                }

                return $counts;
            }, 3);

            foreach ($counts as $key => $count) {
                $result[$key] += $count;
            }
        }

        return $result;
    }
}
