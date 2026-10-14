<?php

namespace App\Exports\Inventory\InventoryFiscal;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportValidationSap implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $rows) {}

    public function headings(): array
    {
        return [
            'Sku',
            'Descripción',
            'Total de conteo',
            'Total transitorias',
            'Total Fisico',
            'Total Wms',
            'Total Sap',
            'Estatus fisico vs Sap',
            'Diferencia fisico vs Sap',
            'Estatus fisico vs Wms',
            'Diferencia fisico vs Wms',
            'Sap vs Wms',
            'Diferencia Sap vs Wms',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function map($row): array
    {
        return [
            $row->partnumber,
            $row->description,
            $row->total_piezas_conteo,
            $row->total_piezas_transitorias,
            $row->total_piezas_conteo_mas_transitorias,
            $row->stock_wms,
            $row->total_piezas_sap,
            $row->status_conteo_vs_sap,
            $row->diff_fisico_vs_sap,
            $row->status_fisico_vs_wms,
            $row->diff_fisico_vs_wms,
            $row->status_sap_vs_wms,
            $row->diff_sap_vs_wms,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:M1')->getFill()
                    ->applyFromArray([
                        'fillType' => Fill::FILL_SOLID,
                        'rotation' => 0,
                        'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
