<?php

namespace App\Observers\Receipt;

use App\Jobs\Recibos\SendEmailToProviderJob;
use App\Models\Configuration\Warehouses\SystemWarehouse;
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

        $parameterNotificationWarehouse = SystemWarehouse::whereWarehouseid($receiptAsn->warehouseid)->value('notificationsflags');
        if (! empty($parameterNotificationWarehouse) && in_array('MWH-18', (array) $parameterNotificationWarehouse)) {
            SendEmailToProviderJob::dispatch($receiptAsn->id)->onQueue('yms-web');
        }

    }

    public function updated(ReceiptAsn $receiptAsn): void {}

    public function deleted(ReceiptAsn $receiptAsn): void {}

    public function restored(ReceiptAsn $receiptAsn): void {}
}
