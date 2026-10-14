<?php

namespace App\Exports\Shipment\Inverse;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportRequestZareTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'shipmentid',
            'delivery',
            'type',
            'partnumberid',
            'zare',
            'ne',
            'adjust',
            'nc',
        ];
    }

    /**
     * Sample row matching ImportRequestZareInverse expected keys.
     *
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        return [
            [
                'C100123566',
                '3900123456',
                'FIS',
                'SKU-001234',
                'ZARE-001',
                'NE-001',
                'S',
                'NC-001',
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

                $sheet->getStyle('A1:H1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('B2')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                $sheet->getComment('C1')->getText()->createTextRun('Valores permitidos: FIS o VIR');
            },
        ];
    }
}
