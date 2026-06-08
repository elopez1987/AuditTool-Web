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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportShipmentSeals implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
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
        return OrderDeliverie::where('orderdeliveries.centerid', '=', $this->request->centerid)
            ->where('orderdeliveries.warehouseid', '=', $this->request->warehouseid)
            ->leftjoin('shipmentheader as sh', 'orderdeliveries.id', '=', 'sh.orderid')
            ->leftjoin('ordercustomers as oc', 'orderdeliveries.customerid', '=', 'oc.id')
            ->leftjoin('shipmentseals as sl', 'orderdeliveries.id', '=', 'sl.orderid')
            ->select(['orderdeliveries.workday', 'orderdeliveries.shipmentid',
                'orderdeliveries.turnid',
                'sh.delivery',
                'oc.name as cliente',
                'sh.totalcontainersfac as pallets', 'sl.containerseal01', 'sl.containerseal02', 'sl.containerseal03',
            ])
            ->whereBetween('orderdeliveries.workday', [$this->request->workdayin, $this->request->workdayout])
            ->get();
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
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
