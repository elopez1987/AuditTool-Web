<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Traits\General\Responser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportShipmentTrucks implements ToCollection, WithHeadingRow, WithValidation
{
    use Responser;
    public function collection(Collection $rows)
    {
        $centerid = $this->getCenterForUser();
        foreach ($rows as $row) {
            ShipmentTruck::updateOrCreate(
                [
                    'centerid' => $centerid,
                    'truckid' => $row['truckid'],
                ],
                [
                    'name' => $row['name'],
                    'auxiliary05' => \Auth::user()->username,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.truckid' => ['required', 'max:15'],
            '*.name' => ['required', 'string', 'max:50'],
        ];
    }
}
