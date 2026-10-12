<?php

namespace App\Exports\Inventory\InventoryFiscal;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportInventoryStockTransitional implements FromQuery, WithCustomChunkSize, WithCustomCsvSettings, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(private readonly Builder $query) {}

    public function query()
    {
        return (clone $this->query)->orderBy('inventory_transitionals.id');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'Ubicación',
            'Número de parte',
            'Descripción',
            'Cantidad',
        ];
    }

    public function map($row): array
    {
        return [
            $row->inventorylocation->name ?? '',
            $row->inventorypartnumber->prtnum ?? '',
            $row->inventorypartnumber->lngdsc ?? '',
            $row->quantity,
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
        ];
    }
}
