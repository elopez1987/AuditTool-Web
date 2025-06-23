<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\General\Responser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLocations implements ToCollection, WithHeadingRow, WithValidation
{
    use Responser;

    public function collection(Collection $rows)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        foreach ($rows as $row) {
            $zoneid = StorageZone::controlAccessUser()->where('name', trim($row['zone']))->first();
            StorageLocation::updateOrCreate(
                [
                    'centerid' => $centerid,
                    'warehouseid' => $warehouseid,
                    'zoneid' => $zoneid->id,
                    'name' => trim($row['name']),
                ],
                [
                    'locationid' => trim($row['name']),
                    'barcode' => rand(100000, 999999),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'zone' => 'required|exists:storagezones,name',
            'name' => 'required|string|max:50',
        ];
    }
}
