<?php

namespace App\Repositories\Receipt;

use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnData;
use App\Models\Receipt\ReceiptAsnTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReceiptAsnRepository
{
    const string STATUS_IN_PROCESS = 'IP';

    const string STATUS_PREDESCARGA = 'PR';

    const string STATUS_PROGRAMADO = 'PG';

    public function findByShipmentid(string $VoucherId): ?ReceiptAsn
    {
        return ReceiptAsn::controlAccessUser()
            ->where('shipmentid', $VoucherId)
            ->whereIn('statusid', [self::STATUS_PROGRAMADO, self::STATUS_IN_PROCESS, self::STATUS_PREDESCARGA])
            ->first();
    }

    public function findByUuid(string $asnId): ?ReceiptAsn
    {
        return ReceiptAsn::controlAccessUser()
            ->whereIn('statusid', [self::STATUS_PROGRAMADO, self::STATUS_IN_PROCESS, self::STATUS_PREDESCARGA])
            ->where('uuid_asn', $asnId)
            ->first();
    }

    public function updateStartLocationAsn(array $data, ReceiptAsn $receiptAsn): void
    {
        ReceiptAsnTime::updateOrCreate([
            'receiptid' => $receiptAsn->id,
        ], [
            'unloadstartdate' => Carbon::now(),
        ]);

        ReceiptAsnData::updateOrCreate([
            'receiptid' => $receiptAsn->id,
        ], [
            'temperature' => $data['Temperature'],
            'locationid' => $data['LocationId'],
            'user_verification' => Auth::id(),
        ]);

        $receiptAsn->update([
            'statusid' => self::STATUS_IN_PROCESS,
        ]);
    }

    public function updateFinishLocationAsn(ReceiptAsn $receiptAsn): void
    {
        ReceiptAsnTime::where('receiptid', $receiptAsn->id)
            ->update([
                'unloadfinishdate' => Carbon::now(),
            ]);

        $receiptAsn->update([
            'statusid' => self::STATUS_PREDESCARGA,
        ]);

    }
}
