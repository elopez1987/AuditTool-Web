<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Repositories\Receipt\ReceiptExpectedProductsRepository;
use App\Repositories\Receipt\VerificationRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\ValidationException;

final readonly class ReceiptExpectedProductsService
{
    public function __construct(
        private ReceiptExpectedProductsRepository $repository,
        private VerificationRepository $verificationRepository,
        private ConnectionInterface $connection,
        private Factory $validator,
    ) {}

    /**
     * Import MessageBody only; external header identity must be resolved by the integration.
     *
     * @param  array<string, mixed>  $messageBody
     */
    public function import(string $asnId, string $username, array $messageBody): void
    {
        $rules = [];

        foreach (VerificationSource::cases() as $source) {
            $root = $source->value;
            $rules[$root] = ['sometimes', 'array'];
            $rules[$root.'.Items'] = ['required_with:'.$root, 'array'];
            $rules[$root.'.Items.Product'] = ['required_with:'.$root, 'array', 'list'];
            $prefix = $root.'.Items.Product.*.';
            $rules[$prefix.'PartNumberId'] = ['required', 'string', 'max:255'];
            $rules[$prefix.'UoMBase'] = ['required', 'string', 'max:30'];
            $rules[$prefix.'Description'] = ['sometimes', 'string', 'max:255'];
            $rules[$prefix.'BarCode'] = ['sometimes', 'string', 'max:255'];
            $fields = $source === VerificationSource::StockRoute
                ? ['QuantityRotary', 'QuantityDefinitive', 'QuantityInvoice', 'QuantityCreditNote', 'QuantityGifts', 'QuantitySelled', 'QuantityLoaded']
                : ['Quantity', 'QuantityRotary', 'QuantityDefinitive', 'QuantityMissing'];

            foreach ($fields as $field) {
                $rules[$prefix.$field] = ['required', 'numeric', 'regex:/^\d{1,14}(?:\.\d{1,4})?$/'];
            }
        }

        $validated = $this->validator->make($messageBody, $rules)->validate();

        if ($validated === []) {
            throw ValidationException::withMessages(['MessageBody' => ['Envía StockRoute o CreditNote.']]);
        }

        $this->connection->transaction(function () use ($asnId, $username, $validated): void {
            $asn = $this->verificationRepository->lockAsn($asnId, $username);

            if ($asn === null) {
                throw new ApiEntityNotFoundException('Vale no encontrado.');
            }

            if ($asn->statusid !== 'PG' || $this->verificationRepository->hasAnyVerifications((int) $asn->id)) {
                throw new ApiBusinessRuleException('No se permite modificar el esperado después de iniciar la recepción.');
            }

            $existing = $this->verificationRepository->lines((int) $asn->id, true);
            $allProducts = array_merge(
                $validated['StockRoute']['Items']['Product'] ?? [],
                $validated['CreditNote']['Items']['Product'] ?? [],
            );
            $products = $this->repository->products($asn, array_column($allProducts, 'PartNumberId'));
            $units = $this->repository->units($asn, array_column($allProducts, 'UoMBase'));
            $rowsBySource = [];

            foreach (VerificationSource::cases() as $source) {
                $seen = [];

                foreach ($validated[$source->value]['Items']['Product'] ?? [] as $item) {
                    $productMatches = $products->get($item['PartNumberId'], collect());
                    $unitMatches = $units->get($item['UoMBase'], collect());

                    if ($productMatches->count() !== 1 || $unitMatches->count() !== 1) {
                        throw ValidationException::withMessages(['MessageBody' => ['Producto o unidad inexistente o ambiguo en el centro y almacén del ASN.']]);
                    }

                    $productId = (int) $productMatches->first()->id;
                    $unitId = (int) $unitMatches->first()->id;
                    $key = $productId.':'.$unitId;
                    $matches = array_filter($existing, fn (ExpectedReceiptLine $line): bool => $line->source === $source && $line->productId === $productId && $line->unitId === $unitId);

                    if (isset($seen[$key]) || count($matches) > 1) {
                        throw ValidationException::withMessages(['MessageBody' => ['Existen líneas repetidas sin identidad externa; se requiere conciliación.']]);
                    }

                    $seen[$key] = true;
                    $rotary = ReceiptQuantity::decimal((string) $item['QuantityRotary']);
                    $definitive = ReceiptQuantity::decimal((string) $item['QuantityDefinitive']);
                    $quantity = $source === VerificationSource::StockRoute
                        ? $rotary->plus($definitive)
                        : ReceiptQuantity::decimal((string) $item['Quantity']);

                    if ($quantity->isGreaterThan('99999999999999.9999')) {
                        throw ValidationException::withMessages(['MessageBody' => ['La cantidad total excede decimal(18,4).']]);
                    }

                    $quantityFields = $source === VerificationSource::StockRoute
                        ? ['QuantityRotary', 'QuantityDefinitive', 'QuantityInvoice', 'QuantityCreditNote', 'QuantityGifts', 'QuantitySelled', 'QuantityLoaded']
                        : ['Quantity', 'QuantityRotary', 'QuantityDefinitive', 'QuantityMissing'];

                    foreach ($quantityFields as $field) {
                        if (strtoupper(trim($item['UoMBase'])) === 'PZ'
                            && ! ReceiptQuantity::decimal((string) $item[$field])->getFractionalPart()->isZero()) {
                            throw ValidationException::withMessages(['MessageBody' => ['La unidad PZ no admite cantidades fraccionadas.']]);
                        }
                    }

                    $stock = $source === VerificationSource::StockRoute;
                    $row = [
                        $stock ? 'receiptid' : 'receiptasn_id' => (int) $asn->id,
                        $stock ? 'partnumberid' : 'inventorypartnumber_id' => $productId,
                        $stock ? 'unitmeasureid' : 'inventoryunitsmeasure_id' => $unitId,
                        'quantity' => (string) $quantity,
                        'rotary' => (string) $rotary,
                        'definitive' => (string) $definitive,
                    ];

                    if ($stock) {
                        foreach (['invoice' => 'QuantityInvoice', 'creditnote' => 'QuantityCreditNote', 'gifts' => 'QuantityGifts', 'selled' => 'QuantitySelled', 'loaded' => 'QuantityLoaded'] as $column => $field) {
                            $row[$column] = (string) ReceiptQuantity::decimal((string) $item[$field]);
                        }
                    } else {
                        $row['quantity_missing'] = (string) ReceiptQuantity::decimal((string) $item['QuantityMissing']);
                    }

                    $rowsBySource[$source->value][] = $row;
                }
            }

            foreach ($rowsBySource as $source => $rows) {
                $this->repository->persist(VerificationSource::from($source), $rows);
            }
        }, 3);
    }
}
