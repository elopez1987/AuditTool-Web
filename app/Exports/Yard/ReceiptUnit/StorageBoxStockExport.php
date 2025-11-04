<?php

namespace App\Exports\Yard\ReceiptUnit;

use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    private $request;

    public function __construct(Request $request)
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
            'Origen',
            'Registro de llegada',
            'Llegada a mesa de control',
            'Enrampe',
            'Inicio de proceso',
            'Fin de proceso',
            'Autorización de salida',
            'Salida de unidad',
            'Ticket de peso',
            'Peso de entrada',
            'Peso de salida',
            'Estadía total',
            'Estatus',
        ];
    }

    public function collection()
    {
        return ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,name', 'warehouse:warehouseid,name', 'unittype:id,description', 'reason:id,name', 'status:statusid,name', 'tiket'])
            ->Carrier($this->request->carrierid)
            ->Rason($this->request->reasonid)
            ->UnitType($this->request->unittypeid)
            ->where('receiptunits.entrydate', '>=', $this->request->workdayin)
            ->where('receiptunits.entrydate', '<=', $this->request->workdayout)
            ->get();
    }

    public function map($unit): array
    {
        // Calcular diferencia entre entrada y salida
        if ($unit->registro && $unit->salida) {
            $inicio = Carbon::parse($unit->registro);
            $fin = Carbon::parse($unit->salida);
            $diffInMinutes = $inicio->diffInMinutes($fin);
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            $diff = sprintf('%02dh %02dm', $hours, $minutes); // formato 02h 15m
        } else {
            $diff = '---'; // o "En proceso" si lo prefieres
        }

        return [
            $unit->warehouse->name,
            Carbon::parse($unit->registro)->format('d/m/Y'),
            $unit->id,
            $unit->barcode,
            $unit->shipmentid,
            $unit->carrier ? $unit->carrier->name : '',
            $unit->boxnumber,
            $unit->trucknumber,
            $unit->operatorname,
            $unit->reason->name,
            $unit->unittype->description,
            $unit->entrydate,
            $unit->deliverdate,
            $unit->ensnare,
            $unit->startdate,
            $unit->finishdate,
            $unit->autorizaoutputdate,
            $unit->outputdate,
            $unit->tiket ? $unit->tiket->ticket : '',
            $unit->tiket ? $unit->tiket->weightin : '',
            $unit->tiket ? $unit->tiket->weightout : '',
            $diff,
            $unit->status->name,
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
