<?php

namespace App\Traits\Web\Yard\ReceiptUnit\DeclineUnit;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentStage;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;

trait DeclineUnitForShipment
{
    public function declineUnitForShipment($receiptunit)
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $receiptunit->id)->first();
        if (! empty($shipment)) {
            if ($shipment->statusid === 'IP') {
                self::updateForRefuseUnit($receiptunit->id);
            } else {
                $shipment->folionumber = null;
                $shipment->auxiliary05 = bin2hex(random_bytes(30));
                $shipment->save();
                self::updateForRefuseUnitTransport($shipment);
            }
        }
    }

    public static function updateForRefuseUnit($folio)
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $folio)->first();
        if (! empty($shipment)) {
            $shipment->folionumber = '';
            $shipment->auxiliary05 = bin2hex(random_bytes(30));
            $shipment->statusid = 'PC';
            $shipment->save();

            $header = ShipmentHeader::controlAccessUser()->where('orderid', $shipment->id)->first();
            $header->startdate = null;
            $header->finishdate = null;
            $header->save();

            $operation = ShipmentDataOperation::controlAccessUser()->where('orderid', $shipment->id)->first();
            $operation->startload = null;
            $operation->endload = null;
            $operation->senddocument = null;
            $operation->save();

            self::updateForRefuseUnitTransport($shipment);
            $stage = ShipmentStage::where('orderid', '=', $shipment->id)->first();
            if (! empty($stage)) {
                $stage->delete();
            }
        }
    }

    public static function updateForRefuseUnitTransport($shipment)
    {
        $transport = StorageShipmentTransport::where('orderid', $shipment->id)->first();
        if (! empty($transport)) {
            $transport->operatorname = '';
            $transport->drivernumber = '';
            $transport->phone = '';
            $transport->boxnumber = '';
            $transport->trucknumber = '';
            $transport->auxiliary01 = null;
            $transport->auxiliary02 = null;
            $transport->save();
        }
    }
}
