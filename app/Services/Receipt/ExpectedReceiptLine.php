<?php

declare(strict_types=1);

namespace App\Services\Receipt;

final readonly class ExpectedReceiptLine
{
    public function __construct(
        public VerificationSource $source,
        public int $id,
        public int $receiptId,
        public int $productId,
        public ?int $unitId,
        public ?string $quantity,
        public ?string $rotary,
        public ?string $definitive,
        public string $partNumberCode,
        public ?string $unitCode,
        public ?string $description,
        public ?string $barcode,
    ) {}

    public function key(): string
    {
        return $this->source->value.':'.$this->id;
    }
}
