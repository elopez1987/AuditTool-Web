<?php

namespace App\Exports\Shipment\IncidencesJourneys;

use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportIncidencesJourneys implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $workdayin;

    private $workdayout;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
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
            'Follio',
            'Fecha de reporte',
            'Respuesta',
            'Fecha de respuesta',
            'Cliente',
            'Destino',
            'Linea de transporte',
            'Embarque',
            'Factura',
            'IDH',
            'Cantidad',
            'Unidad de medida',
            'Concepto',
            'Concepto Cliente',
            'F.Entrega',
            'Con PDF ?',
            'Con Fotos ?',
            'Creador',
            'Respuesta de',
            'Responsable',
        ];
    }

    public function map($shipments): array
    {
        return [
            $shipments->folio,
            $shipments->created_at,
            $shipments->response,
            $shipments->respuesta,
            $shipments->customer,
            $shipments->city,
            $shipments->carriername,
            $shipments->shipmentid,
            $shipments->delivery,
            $shipments->partnumberid,
            $shipments->quantity,
            $shipments->unitmeasure,
            $shipments->razoncode,
            $shipments->tipo,
            $shipments->date,
            $shipments->pdf,
            $shipments->foto,
            $shipments->creadorpor,
            $shipments->respuestade,
            $shipments->cargoa,
        ];
    }

    public function collection()
    {
        return collect(DB::select('exec getReportIncidencesJourneys "' . $this->workdayin . '" , "' . $this->workdayout . '"    '));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:T1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
