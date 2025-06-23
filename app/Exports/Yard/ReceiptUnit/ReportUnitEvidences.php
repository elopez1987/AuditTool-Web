<?php

namespace App\Exports\Yard\ReceiptUnit;

use App\Exports\AfterSheet;
use App\Exports\Fill;
use App\Models\Yard\ReportUnitEvidence;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportUnitEvidences implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    private $workdayin;
    private $workdayout;

    use Exportable;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
    }

    public function headings(): array
    {
        return [
            'C.Distribución',
            'Folio',
            'Embarque',
            'Placa de caja',
            'Economico',
            'Linea de transporte',
            'Tipo de Unidad',
            'Fecha de entrada',
            'Fecha de enrampe',
            'Usuario de enrampe',
            'Zona',
            'Cortina',
            'Inicio de proceso',
            'Fin de proceso',
            'Fotos de entrada',
            'Usuario entrada',
            'Fotos de proceso',
            'Usuario de proceso',
            'Fotos de calidad',
            'Usuario de calidad',
            'Fotos de salida',
            'Usuario salida',
            'Concepto de entrada',
            'Tipo',
            'Estatus',
            '¿En patio?'
        ];
    }

    public function collection()
    {
        $units = ReportUnitEvidence::controlAccessUser()->where('entrydate', '>', $this->workdayin)
            ->where('entrydate', '<', $this->workdayout)->get();
        return $units;
    }

    public function map($units): array
    {


        return [
            $units->centerid,
            $units->folionumber,
            $units->shipmentid,
            $units->boxnumber,
            $units->econumber,
            $units->carriername,
            $units->tuckid,
            $units->entrydate,
            $units->fechaenrampe,
            $units->usuarioenrampe,
            $units->zonename,
            $units->locationname,
            $units->startdate,
            $units->finishdate,
            $units->fotosentradas,
            $units->usuarioentrada,
            $units->fotosproceso,
            $units->usuarioproceso,
            $units->fotoscalidad,
            $units->usuariocalidad,
            $units->fotossalidas,
            $units->usuariosalida,
            $units->concepto,
            $units->origen,
            $units->status,
            $units->enpatio,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6']]);
            },
        ];
    }



}
