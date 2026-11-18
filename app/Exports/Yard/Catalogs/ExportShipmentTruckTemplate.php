<?php

namespace App\Exports\Yard\Catalogs;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportShipmentTruckTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'truckid',
            'name',
        ];
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        return [
            [
                '53FT',
                'CAJA SECA 53 PIES',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:B1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
