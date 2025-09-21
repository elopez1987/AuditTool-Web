<?php

namespace App\Exports\Shipment\Inverse;

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

class ReportCheckInverse implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
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

    public function headings(): array
    {
        return [
            'Folio',
            'Embarque',
            'Factura',
            'IDH',
            'Descripción',
            'Reportada por linea',
            'Recibida',
            'Inicio de verificación',
            'Fin de verificación',
            'Verificador',
            'Fecha de registro',
            'Ubicación',
        ];
    }

    public function collection()
    {
        return DB::table('storageincidencejourneydetails')
            ->select(
                'storageincidencejourneydetails.folioid',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity as reportada',
                DB::raw('sum(storagescheduleinverseoperationdetails.quantity) as verificada'),
                'storagescheduleinverseoperation.startdate as inicio',
                'storagescheduleinverseoperation.finishdate as fin',
                'users.name as verificador',
                'storagescheduleinverseoperationdetails.created_at as registrada',
                'storagescheduleinverseoperationdetails.value01 as location'
            )
            ->join('orderdeliveries', 'storageincidencejourneydetails.orderid', '=', 'orderdeliveries.id')
            ->join('storagescheduleinverse', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagescheduleinverse.orderid')
                    ->on('storageincidencejourneydetails.centerid', '=', 'storagescheduleinverse.centerid');
            })
            ->join('storagescheduleinverseoperation', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagescheduleinverseoperation.orderid')
                    ->on('storageincidencejourneydetails.centerid', '=', 'storagescheduleinverseoperation.centerid');
            })
            ->join('users', 'storagescheduleinverseoperation.username', '=', 'users.username')
            ->join('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('storagescheduleinverseoperationdetails', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagescheduleinverseoperationdetails.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storagescheduleinverseoperationdetails.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storagescheduleinverseoperationdetails.partnumberid');
            })
            ->where('storageincidencejourneydetails.centerid', '=', $this->centerid)
            ->whereBetween('storagescheduleinverse.created_at', [$this->workdayin, $this->workdayout])
            ->groupBy(
                'storageincidencejourneydetails.folioid',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc',
                'storageincidencejourneydetails.quantity',
                'storagescheduleinverseoperation.startdate',
                'storagescheduleinverseoperation.finishdate',
                'users.name',
                'storagescheduleinverseoperationdetails.created_at',
                'storagescheduleinverseoperationdetails.value01'
            )
            ->orderBy('orderdeliveries.shipmentid', 'asc')
            ->get();
    }

    public function map($shipments): array
    {
        return [
            $shipments->folioid,
            $shipments->shipmentid,
            $shipments->delivery,
            $shipments->partnumberid,
            $shipments->description,
            $shipments->reportada,
            $shipments->verificada,
            $shipments->inicio,
            $shipments->fin,
            $shipments->verificador,
            $shipments->registrada,
            $shipments->location,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
