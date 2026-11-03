<?php

namespace App\Services\Receipt;

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptAsnRepository;

readonly class ReceiptAsnService
{
    const string STATUS_IN_PROCESS = 'IP';

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
    public function updateLocationAsn(array $data): void
    {
        $receiptAsn = $this->asnRepository->findByUuid($data['AsnId']);

        if ($receiptAsn === null) {
            throw new ApiEntityNotFoundException('El recibo no existe o no está disponible para iniciar el proceso.');
        }

        if ($receiptAsn->statusid === self::STATUS_IN_PROCESS) {
            throw new ApiBusinessRuleException('El recibo ya está en estatus en proceso.');
        }

        $this->asnRepository->updateLocationAsn($data, $receiptAsn);
    }
}
