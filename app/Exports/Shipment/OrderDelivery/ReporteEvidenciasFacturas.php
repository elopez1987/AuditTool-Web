<?php

namespace App\Exports\Shipment\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteEvidenciasFacturas implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $workdayin;

    private $workdayout;

    private $centerid;

    public function __construct($workdayin, $workdayout, $centerid)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
        $this->centerid = $centerid;
    }

    public function columnFormats(): array
    {
        return [
            //            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha de trabajo',
            'Embarque',
            'Cliente',
            'Destino',
            'Linea de transporte',
            'Fecha de carga',
        ];
    }

    public function map($shipments): array
    {
        return [
            $shipments->workday,
            $shipments->shipmentid,
            $shipments->customer->name,
            $shipments->customer->city,
            $shipments->carrier->name,
            $shipments->loadingdate,
        ];
    }

    public function collection()
    {
        return OrderDeliverie::controlAccessUser()
            ->where('statusid', '=', 'DH')
            ->whereNull('evidencefile')
            ->whereBetween('loadingdate', [$this->workdayin, $this->workdayout])
            ->get();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:I1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
