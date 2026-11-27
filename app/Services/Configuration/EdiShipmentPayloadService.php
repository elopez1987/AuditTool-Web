<?php

declare(strict_types=1);

namespace App\Services\Configuration;

use App\Exceptions\ApiBusinessRuleException;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptStockCredit;
use App\Repositories\Configuration\EdiShipmentRepository;
use App\Services\Receipt\ReceiptQuantity;
use App\Services\Receipt\VerificationType;
use Brick\Math\BigDecimal;
use Illuminate\Support\Carbon;

final readonly class EdiShipmentPayloadService
{
    public function __construct(
        private EdiShipmentRepository $repository,
    ) {}

    public function createPendingFor(ReceiptAsn $lockedReceipt): void
    {
        $receipt = $this->repository->findReceiptForPayload((int) $lockedReceipt->id);
        $this->repository->createPending($receipt, $this->payload($receipt));
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ReceiptAsn $receipt): array
    {
        $warehouse = $receipt->warehouse;
        $provider = $receipt->provider;
        $operator = $receipt->operator;

        if ($warehouse === null || $provider === null || $operator === null) {
            throw new ApiBusinessRuleException('El vale no tiene almacén, ruta u operador requeridos para generar el mensaje EDI.');
        }

        $stockProducts = [];
        foreach ($receipt->stockplan as $stock) {
            $stockProducts[] = $this->stockProduct($receipt, $stock);
        }

        if ($stockProducts === []) {
            throw new ApiBusinessRuleException('El vale no tiene productos StockRoute para generar el mensaje EDI.');
        }

        $messageBody = [
            'StockRoute' => [
                'Items' => [
                    'Product' => $stockProducts,
                ],
            ],
        ];

        $creditProducts = [];
        foreach ($receipt->stockcredits as $credit) {
            $creditProducts[] = $this->creditProduct($credit);
        }

        if ($creditProducts !== []) {
            $messageBody['CreditNote'] = [
                'Items' => [
                    'Product' => $creditProducts,
                ],
            ];
        }

        $date = Carbon::now()->toDateString();

        return [
            'CenterId' => $this->requiredText($warehouse->center, 'CenterId', 30),
            'WarehouseId' => $this->requiredText($warehouse->warehouse, 'WarehouseId', 30),
            'ShipmentId' => $this->requiredText($receipt->shipmentid, 'ShipmentId', 30),
            'RouteId' => $this->requiredText($provider->providerid, 'RouteId', 30),
            'RouteName' => $this->requiredText($provider->name, 'RouteName'),
            'EmployeeId' => $this->requiredText($operator->barcode, 'EmployeeId', 30),
            'EmployeeName' => mb_strtoupper($this->requiredText($operator->name, 'EmployeeName')),
            'PostingDate' => $date,
            'SalesDate' => $date,
            'MessageBody' => $messageBody,
        ];
    }

    /**
     * @return array<string, string|float>
     */
    private function stockProduct(ReceiptAsn $receipt, ReceiptStock $stock): array
    {
        $product = $stock->partnumber;
        $unit = $stock->unitmeasure;

        if ($product === null || $unit === null) {
            throw new ApiBusinessRuleException('Una línea StockRoute no tiene producto o unidad de medida para el mensaje EDI.');
        }

        $rotary = $this->verifiedQuantity($receipt, 'receiptstock_id', (int) $stock->id, VerificationType::Rotary);
        $definitive = $this->verifiedQuantity($receipt, 'receiptstock_id', (int) $stock->id, VerificationType::Definitive);
        $invoice = $this->quantity($stock->getRawOriginal('invoice'), 'QuantityInvoice');
        $creditNote = $this->quantity($stock->getRawOriginal('creditnote'), 'QuantityCreditNote');
        $gifts = $this->quantity($stock->getRawOriginal('gifts'), 'QuantityGifts');
        $selled = $this->quantity($stock->getRawOriginal('selled'), 'QuantitySelled');
        $loaded = $this->quantity($stock->getRawOriginal('loaded'), 'QuantityLoaded');

        $this->validateStockQuantities(
            $rotary,
            $definitive,
            $invoice,
            $creditNote,
            $gifts,
            $selled,
            $loaded,
            (string) $product->prtnum,
        );

        return [
            'PartNumberId' => $this->partNumber((string) $product->prtnum),
            'Description' => $this->requiredText($product->lngdsc, 'Description'),
            'BarCode' => (string) ($product->barcode ?? ''),
            'QuantityRotary' => $this->float($rotary),
            'QuantityDefinitive' => $this->float($definitive),
            'QuantityInvoice' => $this->float($invoice),
            'QuantityCreditNote' => $this->float($creditNote),
            'QuantityGifts' => $this->float($gifts),
            'QuantitySelled' => $this->float($selled),
            'QuantityLoaded' => $this->float($loaded),
            'UoMBase' => $this->unitCode($unit->ediunitmeasurecode),
        ];
    }

    /**
     * @return array<string, string|float>
     */
    private function creditProduct(ReceiptStockCredit $credit): array
    {
        $product = $credit->inventorypartnumber;
        $unit = $credit->inventoryunitsmeasure;

        if ($product === null || $unit === null) {
            throw new ApiBusinessRuleException('Una línea CreditNote no tiene producto o unidad de medida para el mensaje EDI.');
        }

        return [
            'PartNumberId' => $this->partNumber((string) $product->prtnum),
            'Description' => $this->requiredText($product->lngdsc, 'Description'),
            'BarCode' => (string) ($product->barcode ?? ''),
            'Quantity' => $this->float($this->quantity($credit->getRawOriginal('quantity'), 'Quantity')),
            'QuantityRotary' => $this->float($this->quantity($credit->getRawOriginal('rotary'), 'QuantityRotary')),
            'QuantityDefinitive' => $this->float($this->quantity($credit->getRawOriginal('definitive'), 'QuantityDefinitive')),
            'QuantityMissing' => $this->float($this->quantity($credit->getRawOriginal('quantity_missing'), 'QuantityMissing')),
            'UoMBase' => $this->unitCode($unit->ediunitmeasurecode),
        ];
    }

    private function verifiedQuantity(ReceiptAsn $receipt, string $foreignKey, int $lineId, VerificationType $type): BigDecimal
    {
        $quantity = $receipt->verifications
            ->where($foreignKey, $lineId)
            ->where('type_id', $type->value)
            ->sum('quantity');

        return ReceiptQuantity::decimal((string) $quantity);
    }

    private function validateStockQuantities(
        BigDecimal $rotary,
        BigDecimal $definitive,
        BigDecimal $invoice,
        BigDecimal $creditNote,
        BigDecimal $gifts,
        BigDecimal $selled,
        BigDecimal $loaded,
        string $partNumber,
    ): void {
        $received = $rotary->plus($definitive);

        if ($received->isLessThan($creditNote)) {
            throw new ApiBusinessRuleException("El producto {$partNumber} no cumple QuantityRotary + QuantityDefinitive >= QuantityCreditNote.");
        }

        if ($received->plus($gifts)->isGreaterThan($loaded->minus($creditNote)->plus($invoice))) {
            throw new ApiBusinessRuleException("El producto {$partNumber} excede la cantidad permitida para venta.");
        }

        if (! $loaded->isEqualTo($received->plus($gifts)->plus($selled))) {
            throw new ApiBusinessRuleException("El producto {$partNumber} no cumple la conciliación de QuantityLoaded.");
        }
    }

    private function quantity(mixed $value, string $field): BigDecimal
    {
        if ($value === null) {
            throw new ApiBusinessRuleException("{$field} es obligatorio para generar el mensaje EDI.");
        }

        $quantity = ReceiptQuantity::decimal((string) $value);

        if ($quantity->isNegative()) {
            throw new ApiBusinessRuleException("{$field} no puede ser negativo en el mensaje EDI.");
        }

        return $quantity;
    }

    private function partNumber(string $partNumber): string
    {
        if (! preg_match('/^\d{6}$/', $partNumber)) {
            throw new ApiBusinessRuleException('PartNumberId debe tener exactamente seis dígitos, incluidos sus ceros a la izquierda.');
        }

        return $partNumber;
    }

    private function unitCode(mixed $value): string
    {
        $unitCode = strtoupper(trim((string) $value));

        if (mb_strlen($unitCode) !== 2) {
            throw new ApiBusinessRuleException('UoMBase debe provenir de InventoryUnitMeasure y tener dos caracteres.');
        }

        return $unitCode;
    }

    private function requiredText(mixed $value, string $field, ?int $maxLength = null): string
    {
        $text = trim((string) $value);

        if ($text === '' || ($maxLength !== null && mb_strlen($text) > $maxLength)) {
            $limit = $maxLength === null ? 'un valor' : "un máximo de {$maxLength} caracteres";

            throw new ApiBusinessRuleException("{$field} requiere {$limit} para generar el mensaje EDI.");
        }

        return $text;
    }

    private function float(BigDecimal $quantity): float
    {
        return (float) (string) $quantity;
    }
}
