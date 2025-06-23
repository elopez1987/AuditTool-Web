<?php

namespace App\Exports\Shipment\Seals;

use App\Models\Shipments\OrderDeliverie;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportShipmentSeals implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    private $workdayin;
    private $workdayout;

    use Exportable;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
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
            'Embarque',
            'Turno',
            'Sello 01',
            'Sello 02',
            'Sello 03',
            'Factura',
            'Destino',
            'Pallets facturados',
        ];
    }

    public function collection()
    {
        $shipments = OrderDeliverie::controlAccessUser()
            ->leftjoin("shipmentheader as sh", "orderdeliveries.id", "=", "sh.orderid")
            ->leftjoin("ordercustomers as oc", "orderdeliveries.customerid", "=", "oc.id")
            ->leftjoin("shipmentseals as sl", "orderdeliveries.id", "=", "sl.orderid")
            ->select(['orderdeliveries.workday', 'orderdeliveries.shipmentid',
                'orderdeliveries.turnid',
                'sh.delivery',
                'oc.name as cliente',
                'sh.totalcontainersfac as pallets', 'sl.containerseal01', 'sl.containerseal02', 'sl.containerseal03'])
            ->whereBetween('orderdeliveries.workday', [$this->workdayin, $this->workdayout])
            ->get();
        return $shipments;
    }

    public function map($shipments): array
    {
        return [
            $shipments->workday,
            $shipments->shipmentid,
            $shipments->turnid,
            $shipments->containerseal01,
            $shipments->containerseal02,
            $shipments->containerseal03,
            $shipments->delivery,
            $shipments->cliente,
            $shipments->pallets,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:I1')->getFill()
                    ->applyFromArray(['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }


}
