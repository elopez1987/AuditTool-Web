<?php

namespace App\Exports\Receipt\ReceipPallets;

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

class ExportReportOfLpnReceipt implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
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
            'Embarque',
            'Factura',
            'Proveedor',
            'Linea de transporte',
            'Folio de entrada',
            'Placa de caja',
            'Placa de tracto',
            'Número de parte',
            'Descripción',
            'Cantidad',
            'Lpn',
            'Usuario',
            'Fecha de ingreso',
        ];
    }

    public function collection()
    {
        return DB::table('receiptasn')
            ->select(
                'receiptasn.shipmentid',
                'receiptasn.billoflading',
                'receiptproviders.name as provider',
                'shipmentcarriers.name as carrier',
                'receiptasn.folionumber',
                'receiptunits.boxnumber',
                'receiptunits.trucknumber',
                'receiptcontainers.partnumberid',
                'inventorypartnumbers.lngdsc',
                'receiptcontainers.quantity',
                'receiptcontainers.lpnid',
                'users.name as usuario',
                'receiptcontainers.created_at as falta'
            )
            ->leftJoin('receiptcontainers', 'receiptasn.id', '=', 'receiptcontainers.receiptid')
            ->leftJoin('inventorypartnumbers', 'receiptcontainers.partnumberid', '=', 'inventorypartnumbers.prtnum')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('receiptunits', 'receiptasn.folionumber', '=', 'receiptunits.id')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('users', 'receiptcontainers.createuser', '=', 'users.username')
            ->where('receiptasn.centerid', '=', $this->centerid)
            ->where('receiptasn.statusid', '=', 'IG')
            ->whereBetween('receiptasn.addasndate', [$this->workdayin,$this->workdayout])
            ->get();
    }

    public function map($receipts): array
    {
        return [
            $receipts->shipmentid,
            $receipts->billoflading,
            $receipts->provider,
            $receipts->carrier,
            $receipts->folionumber,
            $receipts->boxnumber,
            $receipts->trucknumber,
            $receipts->partnumberid,
            $receipts->lngdsc,
            $receipts->quantity,
            $receipts->lpnid,
            $receipts->usuario,
            $receipts->falta,

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:M1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
