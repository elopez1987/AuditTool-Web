<?php

namespace App\Exports\Receipt\Incidences;

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

class ReportIncidenceReceiptExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
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
            'Fecha de ingreso',
            'Turno',
            'Emisor',
            'Destino',
            'Embarque',
            'Factura',
            'Número de parte',
            'Descripción',
            'Total de cajas delivery',
            'Cajas por pallet',
            'Cantidad reportada con daño',
            'Unidad de medida',
            'Categoria de daño',
            'Sub Categoria',
        ];
    }

    public function collection()
    {
        return DB::table('receiptasn')
            ->select(
                'receiptasn.addasndate as fecha',
                'receiptasn.turnid as turno',
                'receiptproviders.name as provider',
                'receiptasn.centerid',
                'receiptasn.shipmentid',
                'receiptasn.billoflading',
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc as description',
                'receiptstock.quantitytrue as total_de_cajas',
                'inventorypartnumbers.untpal as cajas_por_pallet',
                'receiptincidencestock.quantity as danos',
                'inventoryunitsmeasure.description as unitmeasure',
                'storagecategoryincidences.description as categoria',
                'storagecategoryincidencedetails.description as subcategoria'
            )
            ->leftJoin('receiptincidencestock', 'receiptasn.id', '=', 'receiptincidencestock.receiptid')
            ->leftJoin('inventorypartnumbers', 'receiptincidencestock.partnumberid', '=', 'inventorypartnumbers.id')
            ->leftJoin('storagecategoryincidences', 'receiptincidencestock.categoryid', '=', 'storagecategoryincidences.categoryincid')
            ->leftJoin('storagecategoryincidencedetails', 'receiptincidencestock.categorydetailid', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->leftJoin('receiptstock', function ($join) {
                $join->on('receiptasn.id', '=', 'receiptstock.receiptid')
                    ->on('receiptstock.partnumberid', '=', 'receiptincidencestock.partnumberid');
            })
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('inventoryunitsmeasure', function ($join) {
                $join->on('inventoryunitsmeasure.unitmeasureid', '=', 'receiptincidencestock.unitmeasureid')
                    ->on('inventoryunitsmeasure.centerid', '=', 'receiptincidencestock.centerid');
            })
            ->where('receiptasn.statusid', '=', 'IG')
            ->whereBetween('receiptasn.addasndate', [$this->workdayin, $this->workdayout])
            ->where('receiptasn.centerid', '=', $this->centerid)
            ->where('receiptincidencestock.quantity', '>', 0)->get();
    }

    public function map($receipts): array
    {
        return [
            Carbon::parse($receipts->fecha)->format('d/m/Y'),
            $receipts->turno,
            $receipts->provider,
            $receipts->centerid,
            $receipts->shipmentid,
            $receipts->billoflading,
            $receipts->prtnum,
            $receipts->description,
            $receipts->total_de_cajas,
            $receipts->cajas_por_pallet,
            $receipts->danos,
            $receipts->unitmeasure,
            $receipts->categoria,
            $receipts->subcategoria,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:N1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
