<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Traits\Web\General\Responser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportCarriers implements ToCollection, WithHeadingRow, WithValidation
{
    use Responser;

    public function collection(Collection $rows)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        foreach ($rows as $row) {
            ShipmentCarrier::updateOrCreate(
                [
                    'centerid' => $centerid,
                    'warehouseid' => $warehouseid,
                    'carrierid' => trim($row['carrierid']),
                ],
                [
                    'name' => trim($row['name']),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.carrierid' => ['required'],
            '*.name' => ['required', 'max:255'],
        ];
    }
}
