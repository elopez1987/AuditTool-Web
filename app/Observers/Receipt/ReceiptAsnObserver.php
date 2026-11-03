<?php

namespace App\Observers\Receipt;

use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnData;
use App\Models\Receipt\ReceiptAsnTime;

class ReceiptAsnObserver
{
    public function created(ReceiptAsn $receiptAsn): void
    {
        //        ReceiptAsnData::create([
        //            'receiptid' => $receiptAsn->id,
        //        ]);
        //
        //        ReceiptAsnTime::create([
        //            'receiptid' => $receiptAsn->id,
        //        ]);

    }

    public function updated(ReceiptAsn $receiptAsn): void {}

    public function deleted(ReceiptAsn $receiptAsn): void {}

    public function restored(ReceiptAsn $receiptAsn): void {}
}
