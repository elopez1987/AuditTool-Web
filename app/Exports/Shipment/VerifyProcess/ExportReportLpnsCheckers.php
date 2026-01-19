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

class ExportReportLpnsCheckers implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            //            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha de trabajo',
            'Embarque',
            'Cliente',
            'Lpn',
            'Fecha de escaneo',
            'Verificador',
        ];
    }

    public function map($shipments): array
    {
        return [
            $shipments->workday,
            $shipments->shipmentid,
            $shipments->customername,
            $shipments->lpnid,
            $shipments->scandate,
            $shipments->verificador,
        ];
    }

    public function collection()
    {
        return DB::table('shipmentcontainers')
            ->select(
                'orderdeliveries.workday',
                'orderdeliveries.shipmentid',
                'ordercustomers.name as customername',
                'shipmentcontainers.lpnid',
                'shipmentcontainers.scandate',
                'users.name as verificador',
            )
            ->join('orderdeliveries', function ($join) {
                $join->on('shipmentcontainers.orderid', '=', 'orderdeliveries.id')
                    ->on('shipmentcontainers.centerid', '=', 'orderdeliveries.centerid');
            })
            ->join('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->join('users', function ($join) {
                $join->on('shipmentcontainers.scanuser', '=', 'users.username');
            })
            ->where('orderdeliveries.centerid', $this->request->centerid)
            ->where('orderdeliveries.warehouseid', $this->request->warehouseid)
            ->whereBetween('shipmentcontainers.scandate', [$this->request->workdayin, $this->request->workdayout])
            ->get();
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
}
