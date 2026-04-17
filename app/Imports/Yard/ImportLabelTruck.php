<?php

namespace App\Imports\Yard;

use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Traits\Web\General\GetParameters;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLabelTruck implements ToCollection, WithHeadingRow, WithValidation
{
    use GetParameters;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $barcode = $this->getFolioYMS($row['centerid']);
            StorageLabelTruck::updateOrCreate(
                [
                    'centerid' => trim($row['centerid']),
                    'warehouseid' => trim($row['warehouseid']),
                    'carrierid' => trim($row['carrierid']),
                    'boxnumber' => trim($row['boxnumber']),
                ],
                [
                    'truckid' => trim($row['truckid']),
                    'typeid' => trim($row['typeid']),
                    'econumber' => trim($row['econumber']),
                    'barcode' => $barcode->folionumber,
                ],
            );
        }
    }

    public function rules(): array
    {
        //        return [
        //            '*.centerid' => 'required|exists:systemcenterdistributions,centerid',
        //            '*.warehouseid' => 'required|exists:systemwarehouses,warehouseid',
        //            '*.carrierid' => 'required|exists:shipmentcarriers,id',
        //            '*.boxnumber' => 'required|string|max:50',
        //        ];
    }
}
