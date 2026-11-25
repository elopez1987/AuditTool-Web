<?php

declare(strict_types=1);

namespace App\Services\Receipt;

final readonly class SaveVerificationData
{
    public function __construct(
        public string $asnId,
        public string $partNumberCode,
        public string $quantity,
        public VerificationType $type,
        public ?VerificationSource $source,
        public ?int $lineId,
        public ?string $operationId,
        public ?string $productionDate,
        public ?string $expireDate,
        public ?string $expireCode,
        public int $userId,
        public string $username,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data, int $userId, string $username): self
    {
        return new self(
            $data['AsnId'],
            $data['PartNumberCode'],
            (string) ReceiptQuantity::decimal((string) $data['Quantity']),
            VerificationType::from($data['TypeId']),
            isset($data['Source']) ? VerificationSource::from($data['Source']) : null,
            isset($data['ExpectedLineId']) ? (int) $data['ExpectedLineId'] : null,
            isset($data['ClientOperationId']) ? strtolower($data['ClientOperationId']) : null,
            $data['ProductionDate'] ?? null,
            $data['ExpireDate'] ?? null,
            $data['ExpireCode'] ?? null,
            $userId,
            $username,
        );
    }
}
