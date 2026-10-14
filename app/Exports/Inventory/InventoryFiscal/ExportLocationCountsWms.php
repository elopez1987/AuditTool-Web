<?php

namespace App\Exports\Inventory\InventoryFiscal;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Exporta la conciliación ubicación físico vs WMS en chunks
 * para soportar volúmenes altos (+30k) sin saturar memoria.
 */
class ExportLocationCountsWms implements FromQuery, WithCustomChunkSize, WithCustomCsvSettings, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(private readonly Builder $query) {}

    public function query()
    {
        // Clonar evita mutar el builder original si se reutiliza.
        // orderBy obligatorio para chunk estable en SQL Server.
        return (clone $this->query)->orderBy('id');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'Pasillo',
            'Cédula',
            'Ubicación',
            'Número de parte fisico',
            'Descripción fisico',
            'Cantidad fisico',
            'Número de parte wms',
            'Cantidad wms',
            'Unidad de medida',
            'Estatus de sku',
            'Estatus de cantidad',
        ];
    }

    public function map($row): array
    {
        return [
            $row->aisle_name,
            $row->card_name,
            $row->location_name,
            $row->part_number1,
            $row->description01,
            $row->total_piezas_fisico,
            $row->part_number_wms,
            $row->total_piezas_wms,
            $row->unitmeasure,
            $row->status_sku,
            $row->status_cantidad,
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
