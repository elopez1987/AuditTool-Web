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

class StorageBoxStockExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
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
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Operador',
            'Concepto de entrada',
            'Registro de llegada',
            'Origen',
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
            'Estadía total',
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
        // Calcular diferencia entre entrada y salida
        $diff = null;
        if ($shipments->registro && $shipments->salida) {
            $inicio = Carbon::parse($shipments->registro);
            $fin = Carbon::parse($shipments->salida);
            $diffInMinutes = $inicio->diffInMinutes($fin);
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            $diff = sprintf('%02dh %02dm', $hours, $minutes); // formato 02h 15m
        } else {
            $diff = '---'; // o "En proceso" si lo prefieres
        }

        return [
            $shipments->warehouse,
            Carbon::parse($shipments->registro)->format('d/m/Y'),
            $shipments->id,
            $shipments->barcode,
            $shipments->shipmentid,
            $shipments->linea,
            $shipments->boxnumber,
            $shipments->trucknumber,
            $shipments->operatorname,
            $shipments->unit->reason->name,
            $shipments->unit->unittype->description,
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
            $diff,
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
