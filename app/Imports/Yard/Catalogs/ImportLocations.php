<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Yard\StorageLocation;
use App\Traits\Web\General\Responser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLocations implements ToCollection, WithHeadingRow, WithValidation
{
    use Responser;

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            StorageLocation::updateOrCreate(
                [
                    'centerid' => trim($row['centerid']),
                    'warehouseid' => trim($row['warehouseid']),
                    'zoneid' => trim($row['zoneid']),
                    'name' => trim($row['name']),
                ],
                [
                    'locationid' => trim($row['name']),
                    'barcode' => rand(100000, 999999),
                ],
            );
        }
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'zoneid' => 'required|exists:storagezones,id',
            'name' => 'required|string|max:50',
        ];
    }
}
