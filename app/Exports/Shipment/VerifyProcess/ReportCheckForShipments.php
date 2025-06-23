<?php

namespace App\Exports\Shipment\VerifyProcess;

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

class ReportCheckForShipments implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    private $workdayin;
    private $workdayout;
    private $centerid;

    use Exportable;

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
            'Centro de distribución',
            'Bodega',
            'Fecha de trabajo',
            'Embarque',
            'Cliente',
            'Ciudad',
            'Verificador',
            'Total de pallets',
            'Total de cajas',
            'Fecha de inicio',
            'Fecha final',
//            'Tiempo total',
        ];
    }

    public function map($shipment): array
    {
        return [
            $shipment->centerid,
            $shipment->warehouseid,
            $shipment->workday,
            $shipment->shipmentid,
            $shipment->customername,
            $shipment->city,
            $shipment->username,
            $shipment->totalpallets,
            $shipment->totalcajas,
            $shipment->startdate,
            $shipment->finishdate,
//            $shipment->diffTime,
        ];
    }

    public function collection()
    {
        $startDate = $this->workdayin;
        $endDate = $this->workdayout;

        $shipment = DB::table('shipmentcheck as s')
            ->select('s.centerid', 's.warehouseid', 'o.workday', 's.orderid', 'o.shipmentid',
                'o2.name as customername', 'o2.city', 's.createuser', 'u.name as username',
                's2.totalcontainers as totalpallets', 's2.totalpieces as totalcajas',
                's.startdate', 's.finishdate',
            )
            ->leftJoin('users as u', function ($join) {
                $join->on('s.createuser', '=', 'u.username');
            })
            ->leftJoin('shipmentheader as s2', function ($join) {
                $join->on('s.orderid', '=', 's2.orderid');
                $join->on('s.centerid', '=', 's2.centerid');
            })
            ->leftJoin('orderdeliveries as o', function ($join) {
                $join->on('o.id', '=', 's.orderid');
                $join->on('s.centerid', '=', 'o.centerid');
            })
            ->leftJoin('ordercustomers as o2', function ($join) {
                $join->on('o.customerid', '=', 'o2.id');
                $join->on('o.centerid', '=', 'o2.centerid');
            })
            ->whereNotNull('s.startdate')
            ->whereNotNull('s.finishdate')
            ->where('s.startdate' >= $startDate)
            ->where('s.finishdate' <= $endDate)
            ->get();
        return $shipment;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }
}
