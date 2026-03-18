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
        foreach ($rows as $row) {
            ShipmentCarrier::updateOrCreate(
                [
                    'centerid' => trim($row['centerid']),
                    'warehouseid' => trim($row['warehouseid']),
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
            '*.centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            '*.warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            '*.carrierid' => ['required'],
            '*.name' => ['required', 'max:255'],
        ];
    }
}
