<?php

namespace App\Exports\Receipt\ReceipPallets;

use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReceiptPallets implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    private $workdayin;
    private $workdayout;
    private $centerid;

    use Exportable;

    public function __construct($workdayin, $workdayout, $centerid)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
        $this->centerid = $centerid;
    }


    public function collection()
    {
        $receipts = DB::table('receiptasn')
            ->select('receiptasn.shipmentid', 'receiptasn.billoflading', 'receiptproviders.name as provider',
                'receiptasn.addasndate as cita', 'inventorypartnumbers.lngdsc as tipo','receiptasn.billoflading','receiptasn.oc',
                'storagereceiptpallets.quantity', 'storagereceiptpallets.damage',
                'receiptunits.boxnumber', 'receiptunits.trucknumber','receiptunits.operatorname','receiptunits.id as folio','shipmentcarriers.name as carrier'
            )
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('storagereceiptpallets', 'receiptasn.id', '=', 'storagereceiptpallets.receiptid')
            ->leftJoin('inventorypartnumbers', 'storagereceiptpallets.partnumberid', '=', 'inventorypartnumbers.id')
            ->leftJoin('receiptunits', 'receiptasn.folionumber', '=', 'receiptunits.id')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->where('receiptasn.centerid', '=', $this->centerid)
            ->whereBetween('receiptasn.addasndate', [$this->workdayin, $this->workdayout])
            ->get();

        return $receipts;
    }

    public function headings(): array
    {
        return [
            'Embarque',
            'Factura',
            'Order de compra',
            'Proveedor',
            'Folio de patios',
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Nombre de operador',
            'Fecha de ingreso',
            'Tipo',
            'Cantidad total',
            'Cantidad dañada',
            'Cantidad correcta',
        ];
    }

    public function map($receipts): array
    {
        return [
            $receipts->shipmentid,
            $receipts->billoflading,
            $receipts->oc,
            $receipts->provider,
            $receipts->folio,
            $receipts->carrier,
            $receipts->boxnumber,
            $receipts->trucknumber,
            $receipts->operatorname,
            $receipts->cita,
            $receipts->tipo,
            $receipts->quantity,
            $receipts->damage,
            $receipts->quantity - $receipts->damage
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:N1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }


}


