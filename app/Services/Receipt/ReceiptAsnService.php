<?php

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptAsnRepository;

readonly class ReceiptAsnService
{
    const string STATUS_IN_PROCESS = 'IP';

    const string STATUS_PREDESCARGA = 'PR';

    public function __construct(
        private ReceiptAsnRepository $asnRepository,
    ) {}

    public function findByShipmentid(string $VoucherId): ?ReceiptAsn
    {
        return $this->asnRepository->findByShipmentid($VoucherId);
    }

    /**
     * @param  array{AsnId: string, Temperature: mixed,LocationId:string}  $data
     *
     * @throws ApiBusinessRuleException
     * @throws ApiEntityNotFoundException
     */
    public function updateStartAsn(array $data): void
    {
        $receiptAsn = $this->asnRepository->findByUuid($data['AsnId']);

        if ($receiptAsn === null) {
            throw new ApiEntityNotFoundException('El recibo no existe o no está disponible para iniciar el proceso.');
        }

        if ($receiptAsn->statusid === self::STATUS_IN_PROCESS) {
            throw new ApiBusinessRuleException('El recibo ya está en estatus en proceso.');
        }

        $this->asnRepository->updateStartLocationAsn($data, $receiptAsn);
    }

    public function updateFinishAsn(array $data): void
    {
        $receiptAsn = $this->asnRepository->findByUuid($data['AsnId']);

        if ($receiptAsn === null) {
            throw new ApiEntityNotFoundException('El recibo no existe o no está disponible para iniciar el proceso.');
        }

        if ($receiptAsn->statusid === self::STATUS_PREDESCARGA) {
            throw new ApiBusinessRuleException('El vale ya se encuentra en PRE-DESCARGA.');
        }

        if ($receiptAsn->statusid !== self::STATUS_IN_PROCESS) {
            throw new ApiBusinessRuleException('El recibo no se encuentra en estatus EN PROCESO.');
        }

        $this->asnRepository->updateFinishLocationAsn($receiptAsn);
    }
}
