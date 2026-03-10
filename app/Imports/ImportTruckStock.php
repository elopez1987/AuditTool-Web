<?php

namespace App\Imports;

use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptTruck;
use App\Traits\Web\General\GetParameters;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportTruckStock implements ToCollection, WithHeadingRow
{
    use GetParameters;

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $carrier = ShipmentCarrier::where('name', '=', $row['transporte'])->select('id')->first();
            if (empty($carrier)) {
                continue;
            }
            $barcodeTruck = $this->getFolioYMS($row['center']);
            ReceiptTruck::create([
                'warehouseid' => $row['bodega'],
                'carrierid' => $carrier->id,
                'trucknumber' => $row['placatracto'],
                'econumber' => '0',
                'operatorname' => $row['operador'],
                'phone' => $row['celular'],
                'barcode' => $barcodeTruck->folionumber,
                'entrydate' => Carbon::now(),
            ]);
        }
    }

    public function startRow(): int
    {
        return 1;
    }
}
