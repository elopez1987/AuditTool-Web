<?php

namespace App\Exports\Configuration\WebService;

use App\Models\Configuration\EDI\SystemEventWebService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill as FillAlias;

class ExportLogWebService implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct() {}

    public function headings(): array
    {
        return [
            'C.Distribución',
            'Bodega',
            'Folio',
            'Placa de caja',
            'Servicio',
            'Código',
            'Mensaje',
            'F.Creación',
        ];
    }

    public function collection()
    {
        return SystemEventWebService::controlAccessUser()->get();
    }

    public function map($folios): array
    {
        return [
            $folios->center->name,
            $folios->warehouse->name,
            $folios->folionumber,
            $folios->boxnumber,
            $folios->servicename,
            $folios->code,
            $folios->message,
            $folios->processdate,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:H1')->getFill()
                    ->applyFromArray(['fillType' => FillAlias::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
