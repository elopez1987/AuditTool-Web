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

class ExportTransportShipmentTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'shipment',
            'carrier',
            'name',
            'arrival_date',
            'appointment_date',
        ];
    }

    /**
     * Sample row showing the expected value formats for the import.
     *
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        $arrival = Carbon::now(config('app.timezone'))->addHours(2)->format('Y-m-d H:i');
        $appointment = Carbon::now(config('app.timezone'))->addDays(2)->setTime(7, 30)->format('Y-m-d H:i');

        return [
            [
                'C100123566',
                '701624',
                'TRACUSA LA RUTA DEL SOL',
                $arrival,
                $appointment,
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

                $sheet->getStyle('A1:E1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Keep date columns as text so the template preserves aaaa-mm-dd H:mm.
                $sheet->getStyle('D2:E2')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);

                $sheet->getComment('D1')->getText()->createTextRun('Formato requerido: aaaa-mm-dd H:mm');
                $sheet->getComment('E1')->getText()->createTextRun('Formato requerido: aaaa-mm-dd H:mm');
            },
        ];
    }
}
