<?php

namespace App\Exports\Shipment\OrderDelivery;

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

class ExportOrderPlanTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'workday',
            'shipmentid',
            'customerid',
            'turnid',
            'carrierid',
            'loadingdate',
            'appointdate',
            'confirmation',
            'dvbusiness',
            'responsible',
            'picking',
            'pallets',
            'weight',
        ];
    }

    /**
     * Sample row aligned with OrderImportV2 expected formats.
     *
     * @return array<int, array<int, string|int|float>>
     */
    public function array(): array
    {
        $now = Carbon::now(config('app.timezone'));

        return [
            [
                $now->format('d/m/Y'),
                'C100123566',
                '10001234',
                1,
                '701624',
                $now->copy()->addHours(2)->format('d/m/Y H:i'),
                $now->copy()->addDays(2)->setTime(7, 30)->format('d/m/Y H:i'),
                'CONF-001',
                'DV01',
                'OPERACIONES',
                120,
                8,
                1540.5,
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

                $sheet->getStyle('A1:M1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Preserve date formats as text: dd/mm/aaaa and dd/mm/aaaa H:mm
                $sheet->getStyle('A2')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('F2:G2')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                $sheet->getComment('A1')->getText()->createTextRun('Formato requerido: dd/mm/aaaa');
                $sheet->getComment('F1')->getText()->createTextRun('Formato requerido: dd/mm/aaaa H:mm');
                $sheet->getComment('G1')->getText()->createTextRun('Formato requerido: dd/mm/aaaa H:mm');
            },
        ];
    }
}
