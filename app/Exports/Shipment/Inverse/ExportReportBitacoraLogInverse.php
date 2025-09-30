<?php

namespace App\Exports\Shipment\Inverse;

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

class ExportReportBitacoraLogInverse implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
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
            'C.Distribución',
            'Bodega',
            'Embarque',
            'Tipo de reporte',
            'Factura',
            'Cliente',
            'Linea de transporte',
            'Fecha de reporte',
            'Código reportado',
            'Descripción reportado',
            'Cita de entrega',
            'Solicitud de Zare',
            'Respuesta de Zare',
            'Zare',
            'Solicitud de NE',
            'Respuesta de NE',
            'NE',
            '¿Ajuste?',
            'Solicitud de NC',
            'Respuesta de NC',
            'NC',
            'IDH',
            'Descripción',
            'Categoria',
            'Cantidad reportada',
            'Unidad de medida reportada',
            'Total de piezas reportadas',
            'Cantidad verificada',
            'Unidad de medida verificada',
            'Total de piezas verificada',
            'Diferencia',
            'Fecha de ingreso de unidad',
            'Incio de verificación',
            'Fin de verificación',
            'Estatus de Devolución',
            'Entrada en WMS',
            'Entrada a Almacen',
            'Solicitud de ajuste',
            'Respuesta de ajuste',
            'Estatus de Inventario',
            'Dias transcurridos',
        ];
    }

    public function collection()
    {
        return collect(DB::select('exec getDataScheduleInverse "' . $this->centerid . '" ,   "' . $this->workdayin . '" , "' . $this->workdayout . '"  '));
    }

    public function map($shipments): array
    {
        return [
            $shipments->centerid,
            $shipments->warehouseid,
            $shipments->shipmentid,
            $shipments->typereport,
            $shipments->delivery,
            $shipments->customername,
            $shipments->carriername,
            $shipments->freporte,
            $shipments->code,
            $shipments->codedescription,
            $shipments->appointment,
            $shipments->requestzare,
            $shipments->responsezare,
            $shipments->zare,
            $shipments->requestne,
            $shipments->responsene,
            $shipments->ne,
            $shipments->adjust,
            $shipments->requestnc,
            $shipments->responsenc,
            $shipments->nc,
            $shipments->partnumberid,
            $shipments->partdescription,
            $shipments->category,
            $shipments->quantityrepor,
            $shipments->unitmeasurerepor,
            $shipments->totalreportadopiezas,
            $shipments->quantityverf,
            $shipments->unitverf,
            $shipments->totalverificadopiezas,
            $shipments->totalreportadopiezas - $shipments->totalverificadopiezas,
            $shipments->entrydate,
            $shipments->startverf,
            $shipments->finishverf,
            $shipments->statusdev,
            $shipments->entrywms,
            $shipments->entrywarehouse,
            $shipments->soliadj,
            $shipments->respadj,
            $shipments->statusinv,
            $shipments->dias,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AO1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
