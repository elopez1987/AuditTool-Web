<?php

namespace App\Traits\Web\Yard\ReceiptUnit\DeclineUnit;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentStage;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use Ramsey\Uuid\Uuid;

trait DeclineUnitForShipment
{
    public function declineUnitForShipment($receiptunit): void
    {
        $shipment = OrderDeliverie::controlAccessUser()
            ->where('folionumber', $receiptunit->id)
            ->first();

        if (empty($shipment)) {
            return;
        }

        if ($shipment->statusid === 'IP') {
            self::updateForRefuseUnit($shipment);

            return;
        }

        $shipment->update(['folionumber' => null]);
        self::updateForRefuseUnitTransport($shipment);
    }

    public static function updateForRefuseUnit(OrderDeliverie $shipment): void
    {
        $shipment->update([
            'folionumber' => '',
            'statusid' => 'PC',
        ]);

        ShipmentHeader::controlAccessUser()
            ->where('orderid', $shipment->id)
            ->first()
            ?->update(['startdate' => null, 'finishdate' => null]);

        ShipmentDataOperation::controlAccessUser()
            ->where('orderid', $shipment->id)
            ->first()
            ?->update(['startload' => null, 'endload' => null, 'senddocument' => null]);

        self::updateForRefuseUnitTransport($shipment);

        ShipmentStage::where('orderid', $shipment->id)
            ->first()
            ?->delete();
    }

    public static function updateForRefuseUnitTransport(OrderDeliverie $shipment): void
    {
        StorageShipmentTransport::where('orderid', $shipment->id)
            ->first()
            ?->update([
                'operatorname' => '',
                'drivernumber' => '',
                'phone' => '',
                'boxnumber' => '',
                'trucknumber' => '',
                'has_data' => false,
                'has_registered' => false,
                'auxiliary05' => Uuid::uuid4()->toString(),
            ]);
    }
}
