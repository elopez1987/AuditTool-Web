<?php

namespace App\Exports\Yard\Locations;

use App\Models\Yard\StorageLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryLocationExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Zona',
            'Ubicación',
            'Estatus',
            'Placas de Caja',
        ];
    }

    public function collection()
    {
        return StorageLocation::controlAccessUser()
            ->select(['zoneid', 'id', 'locationid', 'auxiliary01', 'statusid'])
            ->get();
    }

    public function map($locations): array
    {
        return [
            $locations->zone->name,
            $locations->locationid,
            $locations->status->name,
            $locations->auxiliary01,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:E1')->getFill()
                    ->applyFromArray(['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
