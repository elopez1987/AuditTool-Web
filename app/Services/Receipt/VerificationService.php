<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\VerificationRepository;
use Closure;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final readonly class VerificationService
{
    public function __construct(
        private VerificationRepository $repository,
        private ConnectionInterface $connection,
    ) {}

    /**
     * @param  null|Closure(ReceiptAsn): string  $storeEvidence
     * @return array{receiptAsn: array<string, mixed>, verification: array<string, mixed>, balance: array<string, mixed>, replayed: bool}
     */
    public function save(SaveVerificationData $data, ?Closure $storeEvidence = null): array
    {
        return $this->connection->transaction(function () use ($data, $storeEvidence): array {
            $asn = $this->repository->lockAsn($data->asnId, $data->username);

            if ($asn === null) {
                throw new ApiEntityNotFoundException('Vale no encontrado.');
            }

            $fingerprint = hash('sha256', json_encode([
                $data->partNumberCode, $data->quantity, $data->type->value,
                $data->source?->value, $data->lineId,
                $data->productionDate, $data->expireDate, $data->expireCode,
                $data->categoryId,
                $data->evidencePath === null ? null : hash('sha256', $data->evidencePath),
            ], JSON_THROW_ON_ERROR));
            $previous = $data->operationId === null ? null : $this->repository->operation((int) $asn->id, $data->operationId);

            if ($previous !== null) {
                if (! hash_equals((string) $previous->request_fingerprint, $fingerprint)) {
                    throw new ApiBusinessRuleException('ClientOperationId ya fue utilizado con otros datos.');
                }

                return [
                    'receiptAsn' => $asn->toArray(),
                    'verification' => $previous->toArray(),
                    'balance' => $previous->balance_snapshot ?? [],
                    'replayed' => true,
                ];
            }

            if ($asn->statusid !== 'IP') {
                throw new ApiBusinessRuleException('El recibo debe estar EN PROCESO para registrar producto.');
            }

            $candidates = array_values(array_filter(
                $this->repository->lines((int) $asn->id, true),
                fn (ExpectedReceiptLine $line): bool => $line->partNumberCode === $data->partNumberCode
                    && ($data->source === null || ($line->source === $data->source && $line->id === $data->lineId)),
            ));

            if ($candidates === []) {
                throw new ApiEntityNotFoundException('Producto o línea no solicitado en el vale.');
            }

            if (count($candidates) !== 1) {
                throw ValidationException::withMessages([
                    'ExpectedLineId' => ['El producto tiene varios orígenes o líneas; envía Source y ExpectedLineId.'],
                ]);
            }

            $line = $candidates[0];

            if ($this->repository->hasUnlinkedVerifications((int) $asn->id, $line->productId)) {
                throw new ApiBusinessRuleException('El producto tiene verificaciones históricas sin conciliar.');
            }

            if ($line->unitId === null || $line->unitCode === null) {
                throw ValidationException::withMessages(['ExpectedLineId' => ['La línea no tiene una unidad de medida válida.']]);
            }

            $quantity = ReceiptQuantity::decimal($data->quantity);
            ReceiptQuantity::expected($line->quantity);

            if (strtoupper(trim($line->unitCode)) === 'PZ' && ! $quantity->getFractionalPart()->isZero()) {
                throw ValidationException::withMessages(['Quantity' => ['La unidad PZ no admite cantidades fraccionadas.']]);
            }

            $quantityMissing = null;
            $creditRotary = null;
            $creditDefinitive = null;
            if ($line->source === VerificationSource::CreditNote) {
                $quantityMissing = ReceiptQuantity::expected($line->quantityMissing);
                $creditRotary = ReceiptQuantity::expected($line->rotary);
                $creditDefinitive = ReceiptQuantity::expected($line->definitive);

                if ($quantity->isGreaterThan($quantityMissing)) {
                    throw ValidationException::withMessages([
                        'Quantity' => ['La cantidad registrada excede la cantidad pendiente de la nota de crédito.'],
                    ]);
                }

                match ($data->type) {
                    VerificationType::Rotary => $creditRotary = $creditRotary->plus($quantity),
                    VerificationType::Definitive => $creditDefinitive = $creditDefinitive->plus($quantity),
                };
            }

            $balances = $this->repository->balances((int) $asn->id);
            $balance = $this->balance($line, $balances[$line->key()] ?? []);

            if ($quantity->isGreaterThan($balance['RemainingTotal'])) {
                throw ValidationException::withMessages([
                    'Quantity' => ['La cantidad registrada excede la cantidad disponible del producto.'],
                ]);
            }

            $totals = $balances[$line->key()] ?? [];
            $totals[$data->type->value] = (string) ReceiptQuantity::decimal($totals[$data->type->value] ?? '0')->plus($quantity);
            $newBalance = $this->balance($line, $totals);
            $evidencePath = $data->evidencePath === null ? null : $storeEvidence($asn);
            $verification = $this->repository->create([
                'receiptasn_id' => $asn->id,
                'inventorypartnumber_id' => $line->productId,
                $line->source->foreignKey() => $line->id,
                'inventoryunitsmeasure_id' => $line->unitId,
                'quantity' => $data->quantity,
                'type_id' => $data->type->value,
                'productiondate' => $data->productionDate,
                'expiredate' => $data->expireDate,
                'expirecode' => $data->expireCode,
                'catetory_incidence_id' => $data->categoryId,
                'evidence_path' => $evidencePath,
                'created_by' => $data->userId,
                'client_operation_id' => $data->operationId ?? (string) Str::uuid(),
                'request_fingerprint' => $fingerprint,
                'balance_snapshot' => $newBalance,
            ]);

            if ($quantityMissing !== null) {
                $this->repository->updateCreditBalances(
                    $line->id,
                    (string) $quantityMissing->minus($quantity),
                    (string) $creditRotary,
                    (string) $creditDefinitive,
                );
            }

            return [
                'receiptAsn' => $asn->toArray(),
                'verification' => $verification->toArray(),
                'balance' => $newBalance,
                'replayed' => false,
            ];
        }, 3);
    }

    public function confirmFinish(int $receiptId, bool $confirmShortages, ?string $reason, int $userId): void
    {
        if ($this->repository->hasUnlinkedVerifications($receiptId)) {
            throw new ApiBusinessRuleException('El recibo tiene verificaciones históricas sin conciliar.');
        }

        $summary = $this->summary($receiptId);
        $hasShortages = false;

        foreach ($summary as $line) {
            ReceiptQuantity::expected($line['ExpectedTotal']);

            if ($line['UnitId'] === null) {
                throw new ApiBusinessRuleException('El recibo tiene líneas sin unidad de medida.');
            }

            $hasShortages = $hasShortages || ReceiptQuantity::decimal($line['RemainingTotal'])->isPositive();
        }

        if ($hasShortages && (! $confirmShortages || trim($reason ?? '') === '')) {
            throw ValidationException::withMessages([
                'ConfirmShortages' => ['Existen cantidades pendientes; confirma los faltantes e indica el motivo para finalizar.'],
            ]);
        }

        if ($hasShortages) {
            $this->repository->recordShortageConfirmation($receiptId, $userId, trim($reason));
        }
    }

    /** @return list<array<string, mixed>> */
    public function summary(int $receiptId): array
    {
        $balances = $this->repository->balances($receiptId);
        $unlinkedProducts = $this->repository->unlinkedProductIds($receiptId);

        return array_map(
            function (ExpectedReceiptLine $line) use ($balances, $unlinkedProducts): array {
                $balance = $this->balance($line, $balances[$line->key()] ?? []);
                $balance['RequiresReconciliation'] = in_array($line->productId, $unlinkedProducts, true);
                $balance['CanReceive'] = $balance['CanReceive'] && ! $balance['RequiresReconciliation'];

                return $balance;
            },
            $this->repository->lines($receiptId),
        );
    }

    /** @param array<string, string> $totals
     * @return array<string, mixed>
     */
    private function balance(ExpectedReceiptLine $line, array $totals): array
    {
        if (array_diff(array_keys($totals), array_column(VerificationType::cases(), 'value')) !== []) {
            throw new ApiBusinessRuleException('La línea tiene clasificaciones históricas no válidas; requiere conciliación.');
        }

        $expected = $line->quantity === null ? null : ReceiptQuantity::decimal($line->quantity);
        $rotary = ReceiptQuantity::decimal($totals[VerificationType::Rotary->value] ?? '0');
        $definitive = ReceiptQuantity::decimal($totals[VerificationType::Definitive->value] ?? '0');
        $total = $rotary->plus($definitive);

        if ($rotary->isNegative() || $definitive->isNegative()) {
            throw new ApiBusinessRuleException('La línea tiene cantidades históricas negativas; requiere conciliación.');
        }

        if ($expected !== null && ! $expected->isNegative() && $total->isGreaterThan($expected)) {
            throw new ApiBusinessRuleException('La línea tiene una recepción histórica excedida; requiere conciliación.');
        }

        return [
            'Source' => $line->source->value,
            'ExpectedLineId' => $line->id,
            'PartNumberCode' => $line->partNumberCode,
            'Description' => $line->description,
            'BarCode' => $line->barcode,
            'UnitId' => $line->unitId,
            'UoMBase' => $line->unitCode,
            'ExpectedTotal' => $expected === null ? null : (string) $expected,
            'VerifiedTotal' => (string) $total,
            'RemainingTotal' => $expected === null || $expected->isNegative() ? null : (string) $expected->minus($total),
            'CanReceive' => $expected !== null && $expected->isGreaterThan($total) && $line->unitId !== null && $line->unitCode !== null,
            'ExpectedRotary' => $line->rotary === null ? null : (string) ReceiptQuantity::decimal($line->rotary),
            'ExpectedDefinitive' => $line->definitive === null ? null : (string) ReceiptQuantity::decimal($line->definitive),
            'VerifiedRotary' => (string) $rotary,
            'VerifiedDefinitive' => (string) $definitive,
            'RotaryVariance' => $line->rotary === null ? null : (string) $rotary->minus($line->rotary),
            'DefinitiveVariance' => $line->definitive === null ? null : (string) $definitive->minus($line->definitive),
            'ExpectedBreakdownMismatch' => $line->source === VerificationSource::StockRoute
                && ($expected === null || $line->rotary === null || $line->definitive === null
                    || ! $expected->isEqualTo(ReceiptQuantity::decimal($line->rotary)->plus($line->definitive))),
        ];
    }
}
