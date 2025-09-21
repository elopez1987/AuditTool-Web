<?php

namespace App\Traits\Web\Yard\ReceiptUnit\DeclineUnit;

use App\Models\Receipt\ReceiptAsn;
use Auth;

trait DeclineUnitForReceipt
{
    public function declineUnitForReceipt($folio)
    {
        $receipt = ReceiptAsn::controlAccessUser()->where('folionumber', $folio)->first();
        if (! empty($receipt)) {
            $receipt->statusid = 'CE';
            $receipt->cancellationdate = now();
            $receipt->auxiliary01 = Auth::user()->name;
            $receipt->save();
        }
    }
}
