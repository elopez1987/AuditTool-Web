<?php

namespace App\Exports\Yard\ReceiptUnit;

use App\Models\Yard\ReportUnitEvidence;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportUnitEvidences implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'C.Distribución',
            'Folio',
            'Embarque',
            'Placa de caja',
            'Económico',
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
            '¿En patio?',
        ];
    }

    public function collection()
    {
        return ReportUnitEvidence::controlAccessUser()
            ->Carrier($this->request->carrierid)
            ->UnitType($this->request->unittypeid)
            ->Rason($this->request->reasonid)
            ->where('entrydate', '>', $this->request->workdayin)
            ->where('entrydate', '<', $this->request->workdayout)->get();
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
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
