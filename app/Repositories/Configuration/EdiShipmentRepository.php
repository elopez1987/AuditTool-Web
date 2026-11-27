<?php

declare(strict_types=1);

namespace App\Repositories\Configuration;

use App\Models\Configuration\EdiShipment;
use App\Models\Receipt\ReceiptAsn;

final class EdiShipmentRepository
{
    public function findReceiptForPayload(int $receiptId): ReceiptAsn
    {
        return ReceiptAsn::query()
            ->with([
                'warehouse',
                'provider',
                'operator',
                'stockplan.partnumber',
                'stockplan.unitmeasure',
                'stockcredits.inventorypartnumber',
                'stockcredits.inventoryunitsmeasure',
                'verifications',
            ])
            ->findOrFail($receiptId);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createPending(ReceiptAsn $receipt, array $payload): EdiShipment
    {
        return EdiShipment::query()->create([
            'centerid' => $receipt->centerid,
            'warehouseid' => $receipt->warehouseid,
            'shipmentid' => $receipt->shipmentid,
            'request_payload' => $payload,
            'status' => 'Pending',
        ]);
    }
}
