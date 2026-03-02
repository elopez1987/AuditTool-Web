<?php

namespace App\Imports;

use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptTruck;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportTruckStock implements ToModel
{
    public function model(array $row)
    {
        $carrier = ShipmentCarrier::where('name', '=', trim($row['transporte']))
            ->select('id')->first();
        ReceiptTruck::create([
            'warehouseid' => $row['warehouseid'],
            'carrierid' => $carrier->id,
            'trucknumber' => $row['placa'],
            'econumber' => $row['econumber'],
            'operatorname' => $row['operatorname'],
            'phone' => $row['phone'],
            'barcode' => $row['barcode'],
            'entrydate' => $row['entrydate'],
        ]);
    }
}
