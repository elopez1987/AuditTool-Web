<?php

namespace App\Observers\Receipt;

use App\Models\Receipt\ReceiptAsn;

class ReceiptAsnObserver
{
    public function created(ReceiptAsn $receiptAsn): void {}

    public function updated(ReceiptAsn $receiptAsn): void {}

    public function deleted(ReceiptAsn $receiptAsn): void {}

    public function restored(ReceiptAsn $receiptAsn): void {}
}
