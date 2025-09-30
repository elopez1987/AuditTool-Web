<?php

namespace App\Exports\Yard\ReceiptUnit;

use App\Models\Yard\StorageBoxStock;
use Carbon\Carbon;
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

class StorageBoxStockExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;
    private $workdayin;
    private $workdayout;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
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
            'Bodega',
            'Fecha',
            'Folio',
            'C.Barra',
            'Embarque',
            'Tipo',
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Operador',
            'Registro de llegada',
            'Llegada a mesa de control',
            'Enrampe',
            'Inicio de proceso',
            'Fin de proceso',
            'Autorización de salida',
            'Salida de unidad',
            'Tiempo para entrar',
            'Tiempo para inicio de carga',
            'Tiempo de carga',
            'Tiempo de salida',
            'Ticket de peso',
            'Peso de entrada',
            'Peso de salida',
            'Estatus',
        ];
    }

    public function collection()
    {
        return StorageBoxStock::controlAccessUser()
            ->where('vw_storageboxstock.registro', '>=', $this->workdayin)
            ->where('vw_storageboxstock.registro', '<=', $this->workdayout)->get();
    }

    public function map($shipments): array
    {
        return [
            $shipments->warehouse,
            Carbon::parse($shipments->registro)->format('d/m/Y'),
            $shipments->id,
            $shipments->barcode,
            $shipments->shipmentid,
            $shipments->tipo,
            $shipments->linea,
            $shipments->boxnumber,
            $shipments->trucknumber,
            $shipments->operatorname,
            $shipments->registro,
            $shipments->llegadamesa,
            $shipments->enrampe,
            $shipments->inicioproceso,
            $shipments->finproceso,
            $shipments->autorizasalida,
            $shipments->salida,
            $shipments->tentrada,
            $shipments->tiniciocarga,
            $shipments->tcarga,
            $shipments->tsalida,
            $shipments->ticket,
            $shipments->weightin,
            $shipments->weightout,
            $shipments->status,
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
