<?php

namespace App\Exports\Shipment\Inverse;

use App\Models\Shipments\StorageScheduleInverse;
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

class ExportReportBitacoraLogInverse implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'C.Distribución',
            'Bodega',
            'Embarque',
            'Tipo de reporte',
            'Factura',
            'Cliente',
            'Linea de transporte',
            'Fecha de reporte',
            'Código reportado',
            'Descripción reportado',
            'Cita de entrega',
            'Solicitud de Zare',
            'Respuesta de Zare',
            'Zare',
            'Solicitud de NE',
            'Respuesta de NE',
            'NE',
            '¿Ajuste?',
            'Solicitud de NC',
            'Respuesta de NC',
            'NC',
            'IDH',
            'Descripción',
            'Categoria',
            'Cantidad reportada',
            'Unidad de medida reportada',
            'Total de piezas reportadas',
            'Cantidad verificada',
            'Unidad de medida verificada',
            'Total de piezas verificada',
            'Diferencia',
            'Fecha de ingreso de unidad',
            'Incio de verificación',
            'Fin de verificación',
            'Estatus de Devolución',
            'Entrada en WMS',
            'Entrada a Almacen',
            'Solicitud de ajuste',
            'Respuesta de ajuste',
            //            'Estatus de Inventario',
            'Dias transcurridos',
        ];
    }

    public function collection()
    {
        //        return collect(DB::select('exec getDataScheduleInverse "' . $this->request->centerid . '" ,   "' . $this->request->workdayin . '" , "' . $this->request->workdayout . '"  '));
        return StorageScheduleInverse::query()
            ->select([
                's.centerid',
                's.warehouseid',
                'o.shipmentid',
                's2.typereport',
                's3.delivery',
                'o2.name                   as customername',
                'sc.name                   as carriername',
                's.adddate',
                's4.auxiliary02            as code',
                's4.description            as codedescription',
                's.appointment',
                's2.requestzare',
                's2.responsezare',
                's2.zare',
                's2.requestne',
                's2.responsene',
                's2.ne',
                's2.adjust',
                's2.requestnc',
                's2.responsenc',
                's2.nc',
                's3.partnumberid',
                'iv.lngdsc                 as partdescription',
                'i.description             as category',
                's3.quantity               as quantityrepor',
                's3.unitmeasure            as unitmeasurerepor',
                DB::raw("
                    IIF(s3.unitmeasure = 'PZ', s3.quantity,
                        IIF(s3.unitmeasure = 'CS', s3.quantity * iv.untcas,
                            IIF(s3.unitmeasure = 'PL', s3.quantity * iv.untcas * iv.untpal, '0')
                        )
                    ) as totalreportadopiezas
                "),
                DB::raw("IIF(s4.categoryincdetailid = '11', '0', s5.quantity) as quantityverf"),
                's5.unitmeasureid          as unitverf',
                DB::raw("
                    IIF(s4.categoryincdetailid = '11', '0',
                        IIF(s5.unitmeasureid = 'PZ', s5.quantity,
                            IIF(s5.unitmeasureid = 'CS', s5.quantity * iv.untcas,
                                IIF(s5.unitmeasureid = 'PL', s5.quantity * iv.untcas * iv.untpal, '0')
                            )
                        )
                    ) as totalverificadopiezas
                "),
                'r2.entrydate',
                's6.startdate              as startverf',
                's6.finishdate             as finishverf',
                's7.name                   as statusdev',
                's6.wmsdate                as entrywms',
                's6.warehousedate          as entrywarehouse',
                's8.requestadjust          as soliadj',
                's8.replyadjust            as respadj',
                's9.name                   as statusinv',
                's10.created_at            as freporte',
                DB::raw('DATEDIFF(day, s10.created_at, s.adddate) as dias'),
            ])

            // ── Alias tabla base ───────────────────────────────────────
            ->from('storagescheduleinverse as s')

            // ── JOINs ──────────────────────────────────────────────────
            ->leftJoin('orderdeliveries as o', function ($join) {
                $join->on('s.orderid', '=', 'o.id')
                    ->on('s.centerid', '=', 'o.centerid')
                    ->on('s.warehouseid', '=', 'o.warehouseid');
            })
            ->leftJoin('storagescheduleinversetraffic as s2', function ($join) {
                $join->on('o.id', '=', 's2.orderid')
                    ->on('o.centerid', '=', 's2.centerid')
                    ->on('o.warehouseid', '=', 's2.warehouseid');
            })
            ->leftJoin('storageincidencejourneydetails as s3', function ($join) {
                $join->on('s3.orderid', '=', 'o.id')
                    ->on('s3.centerid', '=', 'o.centerid')
                    ->on('s3.delivery', '=', 's2.delivery');
            })
            ->leftJoin('ordercustomers as o2', function ($join) {
                $join->on('o.customerid', '=', 'o2.id')
                    ->on('o.centerid', '=', 'o2.centerid')
                    ->on('o.warehouseid', '=', 'o2.warehouseid');
            })
            ->leftJoin('shipmentcarriers as sc', function ($join) {
                $join->on('o.carrierid', '=', 'sc.id')
                    ->on('o.centerid', '=', 'sc.centerid');
            })
            ->leftJoin('storagecategoryincidencedetails as s4',
                's3.razoncode', '=', 's4.categoryincdetailid'
            )
            ->leftJoin('inventorypartnumbers as iv', function ($join) {
                $join->on('iv.prtnum', '=', 's3.partnumberid')
                    ->on('iv.centerid', '=', 's3.centerid')
                    ->on('iv.warehouseid', '=', 's3.warehouseid');
            })
            ->leftJoin('inventorypartnumbercategory01 as i',
                'iv.category01id', '=', 'i.id'
            )
            ->leftJoin('receiptunits as r2', function ($join) {
                $join->on('s.folionumber', '=', 'r2.id')
                    ->on('s.centerid', '=', 'r2.centerid')
                    ->on('s.warehouseid', '=', 'r2.warehouseid');
            })
            ->leftJoin('storagescheduleinverseoperationdetails as s5', function ($join) {
                $join->on('s.id', '=', 's5.inverseid')
                    ->on('o.id', '=', 's5.orderid')
                    ->on('s.centerid', '=', 's5.centerid')
                    ->on('s.warehouseid', '=', 's5.warehouseid')
                    ->on('s3.partnumberid', '=', 's5.partnumberid')
                    ->on('s3.delivery', '=', 's5.delivery');
            })
            ->leftJoin('storagescheduleinverseoperation as s6', function ($join) {
                $join->on('s.orderid', '=', 's6.orderid')
                    ->on('s6.centerid', '=', 's.centerid')
                    ->on('s6.warehouseid', '=', 's.warehouseid');
            })
            ->leftJoin('systemstatus as s7', function ($join) {
                $join->on('s6.statusid', '=', 's7.statusid')
                    ->on('s6.schemaid', '=', 's7.schemaid');
            })
            ->leftJoin('storagescheduleinverseinventory as s8', function ($join) {
                $join->on('s.orderid', '=', 's8.orderid')
                    ->on('s.centerid', '=', 's8.centerid')
                    ->on('s.warehouseid', '=', 's8.warehouseid')
                    ->on('s8.delivery', '=', 's3.delivery');
            })
            ->leftJoin('systemstatus as s9', function ($join) {
                $join->on('s8.statusid', '=', 's9.statusid')
                    ->on('s8.schemaid', '=', 's9.schemaid');
            })
            ->leftJoin('storageincidencejourneys as s10', function ($join) {
                $join->on('o.id', '=', 's10.orderid')
                    ->on('o.centerid', '=', 's10.centerid');
            })

            // ── WHERE ──────────────────────────────────────────────────}
            ->where('s.centerid', $this->request->centerid)
            ->whereBetween('s.adddate', [$this->request->workdayin, $this->request->workdayout])

            // ── GROUP BY ───────────────────────────────────────────────
            ->groupBy([
                'o.shipmentid',
                's.warehouseid',
                's.centerid',
                's2.typereport',
                's3.delivery',
                'o2.name',
                'sc.name',
                's.adddate',
                's4.auxiliary02',
                's4.description',
                's.appointment',
                's2.requestzare',
                's2.responsezare',
                's2.zare',
                's2.requestne',
                's2.responsene',
                's2.ne',
                's2.adjust',
                's2.requestnc',
                's2.responsenc',
                's2.nc',
                's3.partnumberid',
                'iv.lngdsc',
                'i.description',
                's3.quantity',
                's3.unitmeasure',
                's4.categoryincdetailid',
                's5.quantity',
                's5.unitmeasureid',
                'iv.untcas',
                'iv.untpal',
                'r2.entrydate',
                's6.startdate',
                's6.finishdate',
                's7.name',
                's6.wmsdate',
                's6.warehousedate',
                's8.requestadjust',
                's8.replyadjust',
                's9.name',
                's10.created_at',
            ])
            ->get();
    }

    public function map($shipments): array
    {
        return [
            $shipments->centerid,
            $shipments->warehouseid,
            $shipments->shipmentid,
            $shipments->typereport,
            $shipments->delivery,
            $shipments->customername,
            $shipments->carriername,
            $shipments->freporte,
            $shipments->code,
            $shipments->codedescription,
            $shipments->appointment,
            $shipments->requestzare,
            $shipments->responsezare,
            $shipments->zare,
            $shipments->requestne,
            $shipments->responsene,
            $shipments->ne,
            $shipments->adjust,
            $shipments->requestnc,
            $shipments->responsenc,
            $shipments->nc,
            $shipments->partnumberid,
            $shipments->partdescription,
            $shipments->category,
            $shipments->quantityrepor,
            $shipments->unitmeasurerepor,
            $shipments->totalreportadopiezas,
            $shipments->quantityverf,
            $shipments->unitverf,
            $shipments->totalverificadopiezas,
            $shipments->totalreportadopiezas - $shipments->totalverificadopiezas,
            $shipments->entrydate,
            $shipments->startverf,
            $shipments->finishverf,
            $shipments->statusdev,
            $shipments->entrywms,
            $shipments->entrywarehouse,
            $shipments->soliadj,
            $shipments->respadj,
            //            $shipments->statusinv,
            $shipments->dias,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AO1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
