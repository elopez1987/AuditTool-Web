<?php

namespace App\Exports\Receipt\ReceiptAsn;

use App\Models\Receipt\ReceiptAsn;
use Carbon\Carbon;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportReceipt implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'C.Distribución',
            'Bodega',
            'Fecha',
            'Recibo',
            'Factura',
            'O.Compra',
            'Proveedor',
            'Folio Patios',
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Operador',
            'Sellos',
            'F.Entrada',
            'F.Llegada a Mesa',
            'Analista',
            'F.Inicia Descarga',
            'F.Fin Descarga',
            'Montacarguista',
            'F.Autoriza Salida',
            'F.Salida de Unidad',
            'Estadia general',
            'Tiempo de descarga',
            'Estatus',
        ];
    }

    public function collection()
    {
        return ReceiptAsn::query()
            ->select([
                DB::raw('CONVERT(varchar, receiptunits.entrydate, 101) as fecha_trabajo'),
                'systemcenterdistributions.description as centro_distribucion',
                'systemwarehouses.name as bodega',
                'receiptasn.shipmentid as recibo',
                'receiptasn.billoflading as factura',
                'receiptasn.oc as orden_de_compra',
                'receiptproviders.name as proveedor',
                'receiptasn.folionumber as folio_unidad',
                'shipmentcarriers.name as linea_transporte',
                'receiptunits.boxnumber as placa_de_caja',
                'receiptunits.trucknumber as placa_de_tracto',
                'receiptunits.operatorname as operador',
                'receiptunits.containerseal as sello_unidad',
                'receiptunits.entrydate as fecha_entrada',
                'receiptunits.deliverdate as fecha_llegada_operacion',
                DB::raw("(SELECT TOP 1 users.name
                    FROM storageunittrace
                    LEFT JOIN users ON storageunittrace.createuser = users.id
                    WHERE receiptunitid = receiptunits.id
                    AND razonstatusid = '10') as analista"),
                'receiptasn.unloadstartdate as inicio_descarga',
                'receiptasn.unloadfinishdate as fin_descarga',
                DB::raw("(SELECT TOP 1 u.name
                    FROM receiptasnchecklist rc
                    LEFT JOIN users u ON rc.createuser = u.username
                    WHERE rc.receiptid = receiptasn.id
                    AND rc.typeid = 'MT') as montacarguista"),
                'receiptunits.autorizaoutputdate as autorizacion_salida',
                'receiptunits.outputdate as salida_unidad',
                DB::raw('DATEDIFF(Minute, receiptunits.entrydate, receiptunits.outputdate) as estadia_total_minutos'),
                DB::raw('DATEDIFF(minute, receiptasn.unloadstartdate, receiptasn.unloadfinishdate) as estadia_descarga'),
                'systemstatus.name as estatus_unidad',
            ])
            ->leftJoin('receiptunits', function ($join) {
                $join->on('receiptasn.folionumber', '=', 'receiptunits.id')
                    ->on('receiptasn.centerid', '=', 'receiptunits.centerid')
                    ->on('receiptasn.warehouseid', '=', 'receiptunits.warehouseid');
            })
            ->leftJoin('receiptproviders', function ($join) {
                $join->on('receiptasn.providerid', '=', 'receiptproviders.id')
                    ->on('receiptasn.centerid', '=', 'receiptproviders.centerid');
            })
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('systemstatus', function ($join) {
                $join->on('receiptasn.statusid', '=', 'systemstatus.statusid')
                    ->on('receiptasn.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('systemwarehouses', 'receiptasn.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('systemcenterdistributions', 'receiptasn.centerid', '=', 'systemcenterdistributions.centerid')
            ->where('receiptasn.centerid', $this->request->centerid)
            ->whereBetween('receiptasn.addasndate', [$this->request->workdayin, $this->request->workdayout])
            ->where('receiptasn.warehouseid', $this->request->warehouseid)
            ->get();
    }

    public function map($receipts): array
    {
        $duration_unload = '-';
        if ($receipts->unloadstartdate && $receipts->unloadfinishdate) {
            $start = Carbon::parse($receipts->unloadstartdate);
            $finish = Carbon::parse($receipts->unloadfinishdate);
            $diff = $start->diff($finish);

            $duration_unload = sprintf('%d hrs %d min', $diff->h + ($diff->days * 24), $diff->i);
        }

        $stay_duration = '-';
        if ($receipts->entrydate && $receipts->unloadfinishdate) {
            $start = Carbon::parse($receipts->entrydate);
            $finish = Carbon::parse($receipts->unloadfinishdate);
            $diff = $start->diff($finish);

            $stay_duration = sprintf('%d hrs %d min', $diff->h + ($diff->days * 24), $diff->i);
        }

        return [
            $receipts->centro_distribucion,
            $receipts->bodega,
            $receipts->fecha_trabajo,
            $receipts->recibo,
            $receipts->factura,
            $receipts->orden_de_compra,
            $receipts->proveedor,
            $receipts->folio_unidad,
            $receipts->linea_transporte,
            $receipts->placa_de_caja,
            $receipts->placa_de_tracto,
            $receipts->operador,
            $receipts->sello_unidad,
            $receipts->fecha_entrada,
            $receipts->fecha_llegada_operacion,
            $receipts->analista,
            $receipts->inicio_descarga,
            $receipts->fin_descarga,
            $receipts->montacarguista,
            $receipts->autorizacion_salida,
            $receipts->salida_unidad,
            $receipts->estadia_total_minutos,
            $receipts->estadia_descarga,
            $receipts->estatus_unidad,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Y1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
