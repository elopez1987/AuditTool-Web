<?php

declare(strict_types=1);

namespace App\Repositories\Receipt;

use App\Models\Configuration\EDI\SystemEventWebService;
use App\Models\Receipt\ReceiptAsn;

final class VerificationWebServiceAuditRepository
{
    /** @return array{centerid: ?string, warehouseid: ?string} */
    public function receiptContext(?string $asnId, ?string $voucherId): array
    {
        $receipt = null;

        if ($asnId !== null || $voucherId !== null) {
            $receipt = ReceiptAsn::query()
                ->where($asnId !== null ? 'uuid_asn' : 'shipmentid', $asnId ?? $voucherId)
                ->first(['centerid', 'warehouseid']);
        }

        return [
            'centerid' => $receipt?->centerid,
            'warehouseid' => $receipt?->warehouseid,
        ];
    }

    /** @param array<string, mixed> $attributes */
    public function record(array $attributes): void
    {
        SystemEventWebService::query()->create($attributes);
    }
}
