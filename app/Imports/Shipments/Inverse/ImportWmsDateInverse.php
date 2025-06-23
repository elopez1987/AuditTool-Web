<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportWmsDateInverse implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $shipment = OrderDeliverie::searchForShipment(trim($row['shipmentid']));
            if (empty($shipment)) {
                continue;
            }
            $operation = StorageScheduleInverseOperation::searchForOrder($shipment->id);
            if (empty($operation)) {
                continue;
            }
            $operation->wmsdate = Carbon::now();
            $operation->warehousedate = Carbon::now();
            $operation->statusid = 'CI';
            $operation->save();

            $logInversa = StorageScheduleInverse::where('orderid', '=', $shipment->id)->first();
            $logInversa->statusid = 'TD';
            $logInversa->save();
        }
    }


}
