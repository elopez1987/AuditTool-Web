<?php

namespace App\Exports\Receipt\ReceiptAsn;

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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportReceipt implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    private $workdayin;
    private $workdayout;
    private $centerid;

    use Exportable;

    public function __construct($workdayin, $workdayout,$centerid)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
        $this->centerid = $centerid;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Turno',
            'Recibo',
            'Factura',
            'O.Compra',
            'Proveedor',
            'Folio Patios',
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Operador',
            'Sellos',
            'F.Entrada',
            'F.Llegada a Mesa',
            'Analista',
            'F.Inicia Descarga',
            'F.Fin Descarga',
            'Montacarguista',
            'F.Autoriza Salida',
            'F.Salida de Unidad',
            'Estadia',
            'Tipo de entrada',
            'Estatus'
        ];
    }

    public function collection()
    {
        $receipts = collect(DB::select('exec getDataReceipts "' . $this->centerid . '" , "' . $this->workdayin . '" , "' . $this->workdayout . '"   '));
        return $receipts;
    }

    public function map($receipts): array
    {
        return [
            $receipts->workday,
            $receipts->turnid,
            $receipts->shipmentid,
            $receipts->billoflading,
            $receipts->oc,
            $receipts->provider,
            $receipts->folionumber,
            $receipts->carrier,
            $receipts->boxnumber,
            $receipts->trucknumber,
            $receipts->operatorname,
            $receipts->containerseal,
            $receipts->entrydate,
            $receipts->deliverdate,
            $receipts->analyst,
            $receipts->unloadstartdate,
            $receipts->unloadfinishdate,
            $receipts->forklift,
            $receipts->autorizaoutputdate,
            $receipts->outputdate,
            $receipts->stay,
            $receipts->type,
            $receipts->status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:W1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }
}
