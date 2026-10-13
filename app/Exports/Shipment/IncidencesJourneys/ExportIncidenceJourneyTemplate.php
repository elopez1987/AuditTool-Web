<?php

namespace App\Exports\Shipment\IncidencesJourneys;

use Carbon\Carbon;
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

class ExportIncidenceJourneyTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'shipmentid',
            'delivery',
            'razoncode',
            'downloaddate',
            'partnumberid',
            'quantity',
            'unitmeasure',
        ];
    }

    /**
     * @return array<int, array<int, string|int>>
     */
    public function array(): array
    {
        return [
            [
                'C100123566',
                '3900123456',
                'RC01',
                Carbon::now(config('app.timezone'))->format('Y-m-d'),
                'SKU-001234',
                5,
                'CS',
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

                $sheet->getStyle('A1:G1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('D2')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                $sheet->getComment('D1')->getText()->createTextRun('Formato recomendado: aaaa-mm-dd');
            },
        ];
    }
}
