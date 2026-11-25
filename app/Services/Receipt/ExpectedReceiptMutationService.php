<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptExpectedProductsRepository;
use App\Repositories\Receipt\VerificationRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\ValidationException;

final readonly class ExpectedReceiptMutationService
{
    public function __construct(
        private ReceiptExpectedProductsRepository $repository,
        private VerificationRepository $verificationRepository,
        private ConnectionInterface $connection,
    ) {}

    /** @param array{prtnum: string, rotary: mixed, definitive: mixed} $data */
    public function addStock(int $receiptId, string $username, array $data): void
    {
        $this->connection->transaction(function () use ($receiptId, $username, $data): void {
            $asn = $this->editableAsn($receiptId, $username);
            $products = $this->repository->products($asn, [$data['prtnum']])->get($data['prtnum'], collect());

            if ($products->count() !== 1) {
                throw new ApiEntityNotFoundException('Producto inexistente o ambiguo en el centro y almacén del ASN.');
            }

            $product = $products->first();
            $units = $this->repository->units($asn, [(string) $product->stkuom])->get($product->stkuom, collect());

            if ($units->count() !== 1) {
                throw ValidationException::withMessages(['prtnum' => ['El producto no tiene una unidad base válida.']]);
            }

            $rotary = ReceiptQuantity::decimal((string) $data['rotary']);
            $definitive = ReceiptQuantity::decimal((string) $data['definitive']);

            if ($rotary->plus($definitive)->isGreaterThan('99999999999999.9999')
                || (strtoupper(trim($units->first()->unitmeasureid)) === 'PZ'
                    && (! $rotary->getFractionalPart()->isZero() || ! $definitive->getFractionalPart()->isZero()))) {
                throw ValidationException::withMessages(['rotary' => ['Cantidades incompatibles con la unidad o precisión del producto.']]);
            }

            $existing = array_filter($this->verificationRepository->lines($receiptId, true), fn (ExpectedReceiptLine $line): bool => $line->source === VerificationSource::StockRoute && $line->productId === (int) $product->id
            );

            if ($existing !== []) {
                throw new ApiBusinessRuleException('El producto ya tiene una línea esperada; actualiza la existente.');
            }

            $this->repository->persist(VerificationSource::StockRoute, [[
                'receiptid' => $receiptId, 'partnumberid' => (int) $product->id,
                'unitmeasureid' => (int) $units->first()->id,
                'rotary' => (string) $rotary, 'definitive' => (string) $definitive,
                'quantity' => (string) $rotary->plus($definitive),
            ]]);
        }, 3);
    }

    /** @param array{quantity: mixed, expirecode?: ?string} $data */
    public function updateStock(int $stockId, string $username, array $data): void
    {
        $this->mutateStock($stockId, $username, $data);
    }

    public function deleteStock(int $stockId, string $username): void
    {
        $this->mutateStock($stockId, $username, null);
    }

    public function deleteAsn(int $receiptId, string $username): void
    {
        $this->connection->transaction(function () use ($receiptId, $username): void {
            $asn = $this->editableAsn($receiptId, $username);
            $this->repository->deleteAsn($asn);
        }, 3);
    }

    /** @param array{quantity: mixed, expirecode?: ?string}|null $data */
    private function mutateStock(int $stockId, string $username, ?array $data): void
    {
        $this->connection->transaction(function () use ($stockId, $username, $data): void {
            $stock = $this->repository->stock($stockId);

            if ($stock === null) {
                throw new ApiEntityNotFoundException('Línea esperada no encontrada.');
            }

            $receiptId = (int) $stock->receiptid;
            $this->editableAsn($receiptId, $username);

            if ($data === null) {
                $this->repository->deleteStock($stock);

                return;
            }

            $quantity = ReceiptQuantity::decimal((string) $data['quantity']);

            foreach ($this->verificationRepository->lines($receiptId, true) as $line) {
                if ($line->source === VerificationSource::StockRoute && $line->id === $stockId
                    && strtoupper(trim($line->unitCode ?? '')) === 'PZ' && ! $quantity->getFractionalPart()->isZero()) {
                    throw ValidationException::withMessages(['quantity' => ['La unidad PZ no admite cantidades fraccionadas.']]);
                }
            }

            $this->repository->updateStock($stock, [
                'quantity' => (string) $quantity,
                'expirecode' => $data['expirecode'] ?? null,
            ]);
        }, 3);
    }

    private function editableAsn(int $receiptId, string $username): ReceiptAsn
    {
        $asn = $this->verificationRepository->lockAccessibleReceiptId($receiptId, $username);

        if ($asn === null) {
            throw new ApiEntityNotFoundException('Vale no encontrado.');
        }

        if ($asn->statusid !== 'PG' || $this->verificationRepository->hasAnyVerifications($receiptId)) {
            throw new ApiBusinessRuleException('No se permite modificar o eliminar el esperado después de iniciar la recepción.');
        }

        return $asn;
    }
}
