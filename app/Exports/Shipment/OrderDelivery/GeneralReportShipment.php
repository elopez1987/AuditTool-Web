<?php

namespace App\Exports\Shipment\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
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

class GeneralReportShipment implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            // 'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Turno',
            'Embarque',
            '% Avance',
            'Cortina',
            'Operador',
            'Linea de transporte',
            'Placas',
            'Cliente',
            'Destino',
            'Cita de carga',
            'Cita con cliente',
            'Arribo de unidad',
            'Tiempo de trayecto',
            'Liberación de unidad',
            'Hora maxima de salida',
            'Fecha real de salida',
            'División de negocio',
            'Pallets solicitados',
            'Cajas solicitadas',
            'Peso total',
            'Volumen total',
            'Pallets facturados',
            'Cajas facturadas',
            'Estatus',
            'Planeador',
        ];
    }

    public function collection()
    {
        return OrderDeliverie::where('orderdeliveries.centerid', '=', $this->request->centerid)
            ->where('orderdeliveries.warehouseid', '=', $this->request->warehouseid)
            ->leftjoin('storageturns as s', 'orderdeliveries.turnid', '=', 's.id')
            ->leftjoin('systemstatus as s2', 'orderdeliveries.statusid', '=', 's2.statusid')
            ->leftjoin('shipmentheader as s3', 'orderdeliveries.id', '=', 's3.orderid')
            ->leftjoin('storagelocations as s4', 's3.locationid', '=', 's4.id')
            ->leftjoin('receiptunits as r2', 'orderdeliveries.folionumber', '=', 'r2.id')
            ->leftjoin('shipmentcarriers as sh', 'orderdeliveries.carrierid', '=', 'sh.id')
            ->leftjoin('storagelocations as s5', 'r2.locationid', '=', 's5.id')
            ->leftjoin('ordercustomers as o', 'orderdeliveries.customerid', '=', 'o.id')
            ->leftjoin('shipmentdataoperation as s6', 'orderdeliveries.id', '=', 's6.orderid')
            ->select(['orderdeliveries.warehouseid', 'orderdeliveries.id', 'orderdeliveries.auxiliary02 as planeador',
                'workday', 's.name as turno', 'orderdeliveries.shipmentid',
                's2.name as statusname', 's4.name as precarga', 's5.name as cortina',
                'o.name as customer', 'o.city', 'orderdeliveries.loadingdate',
                'orderdeliveries.downloaddate', 'r2.entrydate', 'r2.deliverdate', 'r2.outputdate',
                'orderdeliveries.auxiliary01 as dvnegocio', 'orderdeliveries.auxiliary02 as planer',
                's6.fullpallet', 's6.fullpicking', 's2.statusid', 's6.auxiliary02 as datatransport', 'r2.operatorname',
                'sh.name as carriername', 'r2.boxnumber', 'o.timetransfer', 'r2.autorizaoutputdate', 's3.totalcontainers', 's3.totalpieces',
                's3.totalweight', 's3.totalvolume', 's3.totalcontainersfac', 's3.totalpiecesfac', 's6.auxiliary02 as avance',
            ])
            ->whereBetween('orderdeliveries.workday', [$this->request->workdayin, $this->request->workdayout])
            ->addSelect(DB::raw('DATEADD(HH, - (o.timetransfer / 60), orderdeliveries.downloaddate)  as maxoutputtransport'))
            ->get();
    }

    public function map($shipments): array
    {
        return [
            $shipments->workday,
            $shipments->turno,
            $shipments->shipmentid,
            $shipments->avance,
            $shipments->precarga,
            $shipments->operatorname,
            $shipments->carriername,
            $shipments->boxnumber,
            $shipments->customer,
            $shipments->city,
            $shipments->loadingdate,
            $shipments->downloaddate,
            $shipments->entrydate,
            $shipments->timetransfer / 60,
            $shipments->autorizaoutputdate,
            $shipments->maxoutputtransport,
            $shipments->outputdate,
            $shipments->dvnegocio,
            $shipments->totalcontainers,
            $shipments->totalpieces,
            $shipments->totalweight,
            $shipments->totalvolume,
            $shipments->totalcontainersfac,
            $shipments->totalpiecesfac,
            $shipments->statusname,
            $shipments->planeador,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
