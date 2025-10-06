<?php

namespace App\Exports\Receipt\CircuitPlant;

use App\Models\Yard\ReceiptUnit;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReportCircuitPlant implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $centerid;

    private $entrydate;

    /**
     * @var mixed
     */
    private $finishdate;

    public function __construct($centerid, $workdayin, $workdayout)
    {
        $this->centerid = $centerid;
        $this->entrydate = $workdayin;
        $this->finishdate = $workdayout;
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Linea de transporte',
            'Operador',
            'Placa de caja',
            'Placa de tracto',
            'Tipo de registro',
            'Embarque',
            'Llegada a Cedis',
            'F.Entrada',
            'F.Salida',
            'Estadia',
            'Llegada a mesa de control',
            'Enrampe',
            'Inicio de descarga',
            'Fin de descarga',
            'Autorización de salida',
        ];
    }

    public function collection()
    {
        //        $units = collect(DB::select('exec getDataUnitToOneRoof "' . $this->centerid . '" , "' . $this->entrydate . '" , "' . $this->finishdate . '"   '));
        return ReceiptUnit::select([
            'receiptunits.id',
            'shipmentcarriers.name as linea',
            'receipttrucks.operatorname',
            'receiptunits.boxnumber',
            'receipttrucks.trucknumber',
            'receiptunits.auxiliary03',
            'receiptunits.shipmentid',
            DB::raw("(select shipmentid from storageunittrace where receiptunits.id = storageunittrace.receiptunitid and razonstatusid='2') as shipment"),
            'storagereceiptunitarrive.startdate as arribo',
            'receiptunits.entrydate',
            'receiptunits.outputdate',
            DB::raw('datediff(minute , receiptunits.entrydate, receiptunits.outputdate) as estadia'),
            'receiptunits.deliverdate',
            'receiptunits.ensnare',
            'receiptunits.startdate',
            'receiptunits.finishdate',
            'receiptunits.autorizaoutputdate',
        ])
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagereceiptunitarrive', 'receiptunits.id', '=', 'storagereceiptunitarrive.value01')
            ->leftJoin('receipttrucks', 'receiptunits.foliotruck', '=', 'receipttrucks.id')
            ->whereIn('receiptunits.carrierid', [40286, 40305, 42433, 42434])
            ->whereBetween('receiptunits.entrydate', [$this->entrydate, $this->finishdate])
            ->where([
                ['receiptunits.razonstatusid', '=', '2'],
                ['receiptunits.centerid', '=', $this->centerid],
            ])->get();
    }

    public function map($units): array
    {
        return [
            $units->id,
            $units->linea,
            $units->operatorname,
            $units->boxnumber,
            $units->trucknumber,
            $units->auxiliary03,
            $units->shipment,
            $units->arribo,
            $units->entrydate,
            $units->outputdate,
            $units->estadia,
            $units->deliverdate,
            $units->ensnare,
            $units->startdate,
            $units->finishdate,
            $units->autorizaoutputdate,
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
