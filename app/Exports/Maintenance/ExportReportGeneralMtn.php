<?php

namespace App\Exports\Maintenance;

use App\Models\Maintenance\CheckListLiftTrucks;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReportGeneralMtn implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return CheckListLiftTrucks::select([
            'checklistlifttrucks.serialnumber',
            'storagelifttrucks.numbertruck',
            'checklistlifttrucks.supervisorname',
            'checklistlifttrucks.horometro',
            'checklistlifttrucks.auxiliary01 as comment',
            'users.name',
            'checklistlifttrucks.created_at',
        ])
            ->leftJoin('users', 'checklistlifttrucks.createuser', '=', 'users.username')
            ->leftJoin('storagelifttrucks', 'checklistlifttrucks.serialnumber', '=', 'storagelifttrucks.serialnumber')
            ->where('checklistlifttrucks.centerid', $this->request->centerid)
            ->whereBetween('checklistlifttrucks.created_at', [$this->request->workdayin, $this->request->workdayout])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Número de serie',
            'Número de equipo',
            'Supervisor en turno',
            'Hornmetro',
            'Comentario',
            'Creador',
            'Fecha de creación',
        ];
    }

    public function map($items): array
    {
        return [
            $items->serialnumber,
            $items->numbertruck,
            $items->supervisorname,
            $items->horometro,
            $items->comment,
            $items->name,
            $items->created_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:G1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
