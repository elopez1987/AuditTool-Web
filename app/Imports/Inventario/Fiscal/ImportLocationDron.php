<?php

namespace App\Imports\Inventario\Fiscal;

use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportLocationDron implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        try {
            return new InventoryLocationForDron([
                'centerid' => trim($row['centerid']),
                'warehouseid' => trim($row['warehouseid']),
                'inventoryid' => trim($row['inventoryid']),
                'areaid' => trim($row['areaid']),
                'aisleid' => trim($row['aisleid']),
                'cardid' => trim($row['cardid']),
                'locationid' => trim($row['locationid']),
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            '*.centerid' => ['required', 'string'],
            '*.warehouseid' => ['required', 'string'],
            '*.inventoryid' => ['required', 'integer'],
            '*.locationid' => ['required'],
            '*.cardid' => ['string', 'required'],
            '*.areaid' => ['string', 'required'],
            '*.aisleid' => ['required'],
            '*.unitmeasureid' => ['string', 'required']
        ];
    }


}
