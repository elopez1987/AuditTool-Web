<?php

namespace App\Imports\Yard\Catalogs;

use App\Models\Yard\Customer\OrderCustomer;
use App\Traits\General\Responser;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportOrderCustomer implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;
    use Responser;

    public function model(array $row)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        OrderCustomer::updateOrCreate(
            [
                'centerid' => $centerid,
                'warehouseid' => $warehouseid,
                'shiptoid' => trim($row['shiptoid']),
            ],
            [
                'name' => trim($row['name']),
                'timetransfer' => $row['timetransfer'],
            ]
        );
    }

    public function rules(): array
    {
        return [
            '*.name' => ['string', 'required', 'max:100'],
            '*.shiptoid' => ['required'],
            '*.timetransfer' => ['numeric', 'required', 'min:1'],
        ];
    }

    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }

}
