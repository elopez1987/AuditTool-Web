<?php

namespace App\Imports;

use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptTruck;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportTruckStock implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $carrier = ShipmentCarrier::where('name', '=', trim($row['transporte']))->select('id')->first();
            if (empty($carrier)) {
                continue;
            }
            ReceiptTruck::create([
                'warehouseid' => $row['bodega'],
                'carrierid' => $carrier->id,
                'trucknumber' => $row['placatracto'],
                'econumber' => $row['ecotruck'],
                'operatorname' => $row['operador'],
                'phone' => $row['celular'],
                'barcode' => $row['barcode'],
                'entrydate' => $row['entrydate'],
            ]);
        }
    }

    public function startRow(): int
    {
        return 1;
    }
}
