<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptAsnRepository;
use App\Repositories\Receipt\VerificationRepository;
use Illuminate\Database\ConnectionInterface;

readonly class ReceiptAsnService
{
    const string STATUS_IN_PROCESS = 'IP';

    const string STATUS_PREDESCARGA = 'PR';

    public function __construct(
        private ReceiptAsnRepository $asnRepository,
        private VerificationRepository $verificationRepository,
        private VerificationService $verificationService,
        private ConnectionInterface $connection,
    ) {}

    public function findByShipmentid(string $VoucherId): ?ReceiptAsn
    {
        $receipt = $this->asnRepository->findByShipmentid($VoucherId);

        if ($receipt !== null) {
            $receipt->setAttribute('verification_summary', $this->verificationService->summary((int) $receipt->id));
        }

        return $receipt;
    }

    /**
     * @param  array{AsnId: string, Temperature: mixed,LocationId:string}  $data
     *
     * @throws ApiBusinessRuleException
     * @throws ApiEntityNotFoundException
     */
    public function updateStartAsn(array $data): void
    {
        $this->connection->transaction(function () use ($data): void {
            $receiptAsn = $this->asnRepository->findByUuid($data['AsnId']);

            if ($receiptAsn === null) {
                throw new ApiEntityNotFoundException('El recibo no existe o no está disponible para iniciar el proceso.');
            }

            if ($receiptAsn->statusid === self::STATUS_IN_PROCESS) {
                throw new ApiBusinessRuleException('El recibo ya está en estatus en proceso.');
            }

            if ($receiptAsn->statusid !== 'PG') {
                throw new ApiBusinessRuleException('Solo se puede iniciar un recibo PROGRAMADO; la reapertura requiere autorización.');
            }

            $this->asnRepository->updateStartLocationAsn($data, $receiptAsn);
        }, 3);
    }

    public function updateFinishAsn(array $data): void
    {
        $this->connection->transaction(function () use ($data): void {
            $receiptAsn = $this->verificationRepository->lockAsn($data['AsnId'], $data['Username']);

            if ($receiptAsn === null) {
                throw new ApiEntityNotFoundException('El recibo no existe o no está disponible para iniciar el proceso.');
            }

            if ($receiptAsn->statusid === self::STATUS_PREDESCARGA) {
                throw new ApiBusinessRuleException('El vale ya se encuentra en PRE-DESCARGA.');
            }

            if ($receiptAsn->statusid !== self::STATUS_IN_PROCESS) {
                throw new ApiBusinessRuleException('El recibo no se encuentra en estatus EN PROCESO.');
            }

            $this->verificationService->confirmFinish(
                (int) $receiptAsn->id,
                (bool) ($data['ConfirmShortages'] ?? false),
                $data['ShortageReason'] ?? null,
                $data['UserId'],
            );
            $this->asnRepository->updateFinishLocationAsn($receiptAsn);
        }, 3);
    }
}
