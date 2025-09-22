<?php

namespace App\Exports\Shipment\PODS;

use App\Models\Security\Users\SecurityAccessWarehouse;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReportShipmentPODs implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{

    use Exportable;
    private $workdayin;
    private $workdayout;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
    }

    public function headings(): array
    {
        return [
            'Centro de distribución',
            'Bodega',
            'Embarque',
            'Fecha',
            'Linea de transporte',
            'Cliente',
            'Cita de carga',
            'Cita de entrega',
            'Confirmación',
            'Archivo',
            'Estatus',
        ];
    }

    public function collection()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return DB::table('orderdeliveries')
            ->select(
                'orderdeliveries.centerid as centro',
                'orderdeliveries.warehouseid as bodega',
                'orderdeliveries.shipmentid as embarque',
                'orderdeliveries.workday as fecha',
                'shipmentcarriers.name as linea',
                'ordercustomers.name as cliente',
                'orderdeliveries.loadingdate as citacarga',
                'orderdeliveries.downloaddate as fechacita',
                'orderdeliveries.confirmation',
                DB::raw('count(storageshipmentspods.orderid) as totalpods'),
                'systemstatus.name as estatus'
            )
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('storageshipmentspods', 'orderdeliveries.id', '=', 'storageshipmentspods.orderid')
            ->leftJoin('systemstatus', 'storageshipmentspods.statusid', '=', 'systemstatus.statusid')
            ->where('orderdeliveries.statusid', '=', 'DH')
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereBetween('orderdeliveries.workday', [$this->workdayin, $this->workdayout])
            ->groupBy(
                'orderdeliveries.centerid',
                'orderdeliveries.warehouseid',
                'orderdeliveries.shipmentid',
                'orderdeliveries.workday',
                'shipmentcarriers.name',
                'ordercustomers.name',
                'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate',
                'orderdeliveries.confirmation',
                'systemstatus.name'
            )
            ->get();
    }

    public function map($shipments): array
    {
        if ($shipments->totalpods > 0) {
            $archivo = 'Con POD';
        } else {
            $archivo = 'Sin POD';
        }

        return [
            $shipments->centro,
            $shipments->bodega,
            $shipments->embarque,
            $shipments->fecha,
            $shipments->linea,
            $shipments->cliente,
            $shipments->citacarga,
            $shipments->fechacita,
            $shipments->confirmation,
            $archivo,
            $shipments->estatus,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
