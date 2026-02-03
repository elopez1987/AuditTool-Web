<?php

namespace App\Observers;

use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;

class ReceiptAsnObserver
{
    public function created(ReceiptAsn $receiptAsn): void
    {
        StorageShipmentTransport::updateOrCreate([
            'centerid' => $receiptAsn->centerid,
            'warehouseid' => $receiptAsn->warehouseid,
            'orderid' => $receiptAsn->id,
            'carrierid' => $receiptAsn->carrierid,
            'auxiliary04' => 'ReceiptAsn',
        ]);
    }

    public function updated(ReceiptAsn $receiptAsn): void {}

    public function deleted(ReceiptAsn $receiptAsn): void {}

    public function restored(ReceiptAsn $receiptAsn): void {}
}
