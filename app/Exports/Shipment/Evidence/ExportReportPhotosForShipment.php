<?php

namespace App\Exports\Shipment\Evidence;

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

class ExportReportPhotosForShipment implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
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
            'Fecha de trabajo',
            'Embarque',
            'Cliente',
            'Fotos',
            'Supervisor',
            'Verificador',
            'Montacarguista',
            'Incio de Carga',
            'Fin de Carga',
        ];
    }

    public function map($shipments): array
    {
        return [
            $shipments->workday,
            $shipments->shipmentid,
            $shipments->customer,
            $shipments->fotos,
            $shipments->supervisor,
            $shipments->checker,
            $shipments->forklift,
            $shipments->startload,
            $shipments->endload,
        ];
    }

    public function collection()
    {
        return DB::table('orderdeliveries')
            ->select(
                'orderdeliveries.workday',
                'orderdeliveries.shipmentid',
                'ordercustomers.name as customer',
                DB::raw('count(storageevidencephotos.path) as fotos'),
                'shipmentdataoperation.supervisor',
                'shipmentdataoperation.checker',
                'shipmentdataoperation.forklift',
                'shipmentdataoperation.startload',
                'shipmentdataoperation.endload'
            )
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid');
            })
            ->leftJoin('storageevidencephotos', function ($join) {
                $join->on('orderdeliveries.id', '=', 'storageevidencephotos.model_id')
                    ->where('storageevidencephotos.model_type', '=', 'OrderDelivery');
            })
            ->join('shipmentdataoperation', function ($join) {
                $join->on('orderdeliveries.id', '=', 'shipmentdataoperation.orderid')
                    ->on('orderdeliveries.centerid', '=', 'shipmentdataoperation.centerid');
            })
            ->whereBetween('shipmentdataoperation.startload', [$this->workdayin, $this->workdayout])
            ->groupBy(
                'orderdeliveries.workday',
                'orderdeliveries.shipmentid',
                'ordercustomers.name',
                'shipmentdataoperation.supervisor',
                'shipmentdataoperation.checker',
                'shipmentdataoperation.forklift',
                'shipmentdataoperation.startload',
                'shipmentdataoperation.endload'
            )
            ->orderBy('orderdeliveries.workday', 'asc')
            ->get();
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
