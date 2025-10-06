<?php

namespace App\Exports\Shipment\Pallets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReportPallets implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha de trabajo',
            'Embarque',
            'Cliente',
            'Sku',
            'Descripción',
            'Total de pallets',
        ];
    }

    public function map($shipment): array
    {
        return [
            $shipment->workday,
            $shipment->shipmentid,
            $shipment->customer,
            $shipment->prtnum,
            $shipment->lngdsc,
            $shipment->quantity,
        ];
    }

    public function collection()
    {
        $startDate = $this->workdayin;
        $endDate = $this->workdayout;

        return DB::table('orderdeliveries as o')
            ->leftJoin('ordercustomers as o2', 'o.customerid', '=', 'o2.id')
            ->leftJoin('shipmentpallets as s', 'o.id', '=', 's.orderid')
            ->leftJoin('inventorypartnumbers as i', 's.prtnum', '=', 'i.prtnum')
            ->select(
                'o.workday',
                'o.shipmentid',
                'o2.name as customer',
                's.prtnum',
                'i.lngdsc',
                's.quantity'
            )
            ->whereBetween('o.workday', [$startDate, $endDate])
            ->orderBy('o.workday', 'asc')
            ->get();
    }
}
