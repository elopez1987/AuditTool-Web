<?php

namespace App\Exports\Receipt\ArrivePlan;

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

class ExportPlanArriveReceipt implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'Embarque',
            'Factura',
            'Proveedor',
            'Linea de transporte',
            'Folio de entrada',
            'Placa de caja',
            'Placa de tracto',
            'Fecha de cita',
            'Fecha de ingreso',
            'Registro en mesa de control',
            'Enrampe de unidad',
            'Inicio de descarga',
            'Fin de descarga',
            'Autorización de salida',
            'Salida de unidad',
            'Programador',
        ];
    }

    public function collection()
    {
        return DB::table('receiptasn')
            ->select(
                'receiptasn.shipmentid',
                'receiptasn.billoflading',
                'receiptproviders.name as provider',
                'shipmentcarriers.name as carrier',
                'receiptunits.id as folio',
                'receiptunits.operatorname',
                'receiptunits.boxnumber',
                'receiptunits.trucknumber',
                'receiptasn.addasndate as cita',
                'receiptunits.entrydate as entrada',
                'receiptunits.deliverdate as mesa',
                'receiptunits.ensnare as enrampe',
                'receiptunits.startdate as iniciodescarga',
                'receiptunits.finishdate as findescarga',
                'receiptunits.autorizaoutputdate as autorizacion',
                'receiptunits.outputdate as salida',
                'receiptasn.auxiliary01 as programador'
            )
            ->leftJoin('receiptunits', 'receiptasn.folionumber', '=', 'receiptunits.id')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->where('receiptasn.auxiliary03', '=', 'PR')
            ->where('receiptasn.centerid', '=', $this->centerid)
            ->whereBetween('receiptasn.addasndate', [$this->workdayin, $this->workdayout])
            ->get();
    }

    public function map($receipts): array
    {
        return [
            $receipts->shipmentid,
            $receipts->billoflading,
            $receipts->provider,
            $receipts->carrier,
            $receipts->folio,
            $receipts->boxnumber,
            $receipts->trucknumber,
            $receipts->cita,
            $receipts->entrada,
            $receipts->mesa,
            $receipts->enrampe,
            $receipts->iniciodescarga,
            $receipts->findescarga,
            $receipts->autorizacion,
            $receipts->salida,
            $receipts->programador,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:P1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
