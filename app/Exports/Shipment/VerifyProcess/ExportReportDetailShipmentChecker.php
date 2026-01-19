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

class ExportReportDetailShipmentChecker implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'Sku',
            'Descripción',
            'Cantidad',
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
            $shipments->sku,
            $shipments->description,
            $shipments->quantity,
            $shipments->lpnid,
            $shipments->scandate,
            $shipments->checker,
        ];
    }

    public function collection()
    {
        return DB::table('shipmentstock')
            ->select(
                'orderdeliveries.workday',
                'orderdeliveries.shipmentid',
                'ordercustomers.name as customername',
                'inventorypartnumbers.prtnum as sku',
                'inventorypartnumbers.lngdsc as description',
                'shipmentstock.quantity',
                'shipmentstock.lpnid',
                'users.name as checker',
                'shipmentstock.created_at as scandate',
            )
            ->join('orderdeliveries', function ($join) {
                $join->on('shipmentstock.orderid', '=', 'orderdeliveries.id')
                    ->on('shipmentstock.centerid', '=', 'orderdeliveries.centerid');
            })
            ->join('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid');
            })
            ->join('inventorypartnumbers', function ($join) {
                $join->on('shipmentstock.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('shipmentstock.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->join('users', 'shipmentstock.createuser', '=', 'users.username')
            ->where('orderdeliveries.centerid', $this->request->centerid)
            ->where('orderdeliveries.warehouseid', $this->request->warehouseid)
            ->whereBetween('shipmentstock.created_at', [$this->request->workdayin, $this->request->workdayout])
            ->get();
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
