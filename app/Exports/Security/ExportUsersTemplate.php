<?php

namespace App\Exports\Security;

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

class ExportUsersTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStrictNullComparison, WithStyles
{
    public function headings(): array
    {
        return [
            'centerid',
            'warehouseid',
            'username',
            'name',
            'email',
            'roleid',
        ];
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        return [
            [
                'CD01',
                'WH01',
                'jdoe',
                'JOHN DOE',
                'john.doe@example.com',
                'OPERADOR',
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

                $sheet->getStyle('A1:F1')->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 0,
                    'startColor' => ['rgb' => '64b5f6'],
                ]);

                $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getComment('F1')->getText()->createTextRun(
                    'Puede ser el id numérico del rol o el name del rol (ej. OPERADOR).'
                );
            },
        ];
    }
}
