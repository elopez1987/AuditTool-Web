<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseInventory;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportResponseInventoryLogInverse implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $shipment = OrderDeliverie::searchForShipment(trim($row['shipmentid']));
            if (empty($shipment)) {
                continue;
            }
            $inverse = StorageScheduleInverse::where('orderid', '=', $shipment->id)->first();
            if (empty($inverse)) {
                continue;
            }
            $delivery = $this->getStorageScheduleInverseTraffic($shipment, $row['delivery']);
            if (empty($delivery)) {
                continue;
            }
            $this->createOrUpdateNoteForInventory($inverse, $row);
            $this->closeInverseOperation($shipment);
            $this->closeInverse($inverse);
        }
    }

    public function createOrUpdateNoteForInventory($inverse, $row): void
    {
        StorageScheduleInverseInventory::updateOrCreate(
            [
                'centerid' => $inverse->centerid,
                'warehouseid' => $inverse->warehouseid,
                'orderid' => $inverse->orderid,
                'inverseid' => $inverse->id,
                'delivery' => trim($row['delivery']),
            ],
            [
                'replyadjust' => Carbon::now(),
                'note' => trim($row['note']),
                'statusid' => 'TD',
            ]
        );
    }

    public function closeInverseOperation(OrderDeliverie $shipment): void
    {
        $logOperation = StorageScheduleInverseOperation::where('orderid', '=', $shipment->id)->first();
        $logOperation->statusid = 'CI';
        $logOperation->wmsdate = Carbon::now();
        $logOperation->warehousedate = Carbon::now();
        $logOperation->save();
    }

    public function closeInverse($inverse): void
    {
        $inverse->statusid = 'TD';
        $inverse->save();
    }

    /**
     * @return StorageScheduleInverseTraffic|Builder|Model|object|null
     */
    public function getStorageScheduleInverseTraffic(OrderDeliverie $shipment, $delivery)
    {
        return StorageScheduleInverseTraffic::where('orderid', '=', $shipment->id)
            ->where('delivery', '=', trim($delivery))
            ->where('adjust', '=', 'S')->first();
    }
}
