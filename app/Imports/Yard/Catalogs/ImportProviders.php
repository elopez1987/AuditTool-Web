<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Receipt\ReceiptProvider;
use App\Traits\Web\General\Responser;
use Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportProviders implements ToCollection, WithHeadingRow, WithValidation
{
    use Responser;

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            ReceiptProvider::updateOrCreate(
                [
                    'centerid' => trim($row['centerid']),
                    'warehouseid' => trim($row['warehouseid']),
                    'providerid' => trim($row['providerid']),
                ],
                [
                    'name' => trim($row['name']),
                    'auxiliary05' => Auth::user()->username,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            '*.warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            '*.providerid' => ['required'],
            '*.name' => ['required', 'string', 'max:150'],
        ];
    }
}
