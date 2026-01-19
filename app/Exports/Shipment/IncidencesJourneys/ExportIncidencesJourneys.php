<?php

namespace App\Exports\Shipment\IncidencesJourneys;

use App\Models\Shipments\StorageIncidenceJourneyDetails;
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

class ExportIncidencesJourneys implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'Follio',
            'Fecha de reporte',
            'Respuesta',
            'Fecha de respuesta',
            'Cliente',
            'Destino',
            'Linea de transporte',
            'Embarque',
            'Factura',
            'IDH',
            'Cantidad',
            'Unidad de medida',
            'Concepto',
            'Concepto Cliente',
            'F.Entrega',
            'Con PDF ?',
            'Con Fotos ?',
            'Creador',
            'Respuesta de',
            'Responsable',
        ];
    }

    public function map($shipments): array
    {
        return [
            $shipments->folio,
            $shipments->created_at,
            $shipments->response,
            $shipments->respuesta,
            $shipments->customer,
            $shipments->city,
            $shipments->carriername,
            $shipments->shipmentid,
            $shipments->delivery,
            $shipments->partnumberid,
            $shipments->quantity,
            $shipments->unitmeasure,
            $shipments->razoncode,
            $shipments->tipo,
            $shipments->date,
            $shipments->pdf,
            $shipments->foto,
            $shipments->creadorpor,
            $shipments->respuestade,
            $shipments->cargoa,
        ];
    }

    public function collection()
    {
        return StorageIncidenceJourneyDetails::select([
            'a.created_at',
            'e1.name as response',
            'a.updated_at as respuesta',
            'd.name as customer',
            'd.city',
            'c.name as carriername',
            'b.shipmentid',
            'a.delivery',
            'a.partnumberid',
            'a.quantity',
            'a.unitmeasure',
            'e.description as razoncode',
            'e.auxiliary02 as tipo',
            'a.downloaddate as date',
            DB::raw("iif(b.evidencefile is not null, 'SI','NO') as pdf"),
            DB::raw("iif(count(f.path) > 0,'SI','NO') as foto"),
            'u1.name as creadorpor',
            'u2.name as respuestade',
            'so.description as cargoa',
            's.folioid as folio',
        ])
            ->from('storageincidencejourneydetails as a')
            ->leftJoin('orderdeliveries as b', function ($join) {
                $join->on('a.orderid', '=', 'b.id')
                    ->on('a.centerid', '=', 'b.centerid')
                    ->on('a.warehouseid', '=', 'b.warehouseid');
            })
            ->leftJoin('shipmentcarriers as c', function ($join) {
                $join->on('b.carrierid', '=', 'c.id')
                    ->on('b.centerid', '=', 'c.centerid');
            })
            ->leftJoin('storageincidencejourneys as s', function ($join) {
                $join->on('a.orderid', '=', 's.orderid')
                    ->on('a.centerid', '=', 's.centerid');
            })
            ->leftJoin('ordercustomers as d', function ($join) {
                $join->on('b.customerid', '=', 'd.id')
                    ->on('a.centerid', '=', 'd.centerid')
                    ->on('a.warehouseid', '=', 'd.warehouseid');
            })
            ->leftJoin('storagecategoryincidencedetails as e', function ($join) {
                $join->on('a.razoncode', '=', 'e.categoryincdetailid')
                    ->on('a.centerid', '=', 'e.centerid');
            })
            ->leftJoin('systemstatus as e1', function ($join) {
                $join->on('a.statusiddetail', '=', 'e1.statusid')
                    ->on('a.schemaid', '=', 'e1.schemaid');
            })
            ->leftJoin('storagereasonoutput as so', 'a.auxiliary02', '=', 'so.id')
            ->leftJoin('users as u1', 'a.auxiliary03', '=', 'u1.username')
            ->leftJoin('users as u2', 'a.auxiliary04', '=', 'u2.username')
            ->leftJoin('storageevidencephotos as f', function ($join) {
                $join->on('b.id', '=', 'f.model_id')
                    ->where('f.model_type', '=', 'OrderDelivery');
            })
            ->where('a.centerid', $this->request->centerid)
            ->where('a.warehouseid', $this->request->warehouseid)
            ->whereBetween('b.downloaddate', [$this->request->workdayin, $this->request->workdayout])
            ->groupBy([
                'b.shipmentid',
                'c.name',
                'd.name',
                'd.city',
                'u1.name',
                'u2.name',
                'a.delivery',
                'a.created_at',
                'a.updated_at',
                'a.partnumberid',
                'a.quantity',
                'a.statusiddetail',
                'a.unitmeasure',
                'a.downloaddate',
                'e.description',
                'e.auxiliary02',
                'e1.name',
                'b.evidencefile',
                'so.description',
                's.folioid',
            ])
            ->get();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:T1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
