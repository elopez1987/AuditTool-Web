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

class ExportReportGeneralMtn implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
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
    public function collection()
    {
        return CheckListLiftTrucks::select([
            'checklistlifttrucks.serialnumber',
            'storagelifttrucks.numbertruck',
            'checklistlifttrucks.supervisorname',
            'checklistlifttrucks.horometro',
            'users.auxiliary01 as comment',
            'users.name',
            'users.created_at',
        ])
            ->leftJoin('users', 'checklistlifttrucks.createuser', '=', 'users.username')
            ->leftJoin('storagelifttrucks', 'checklistlifttrucks.serialnumber', '=', 'storagelifttrucks.serialnumber')
            ->where('checklistlifttrucks.centerid', $this->centerid)
            ->whereBetween('checklistlifttrucks.created_at', [$this->workdayin, $this->workdayout])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Número de serie',
            'Número de equipo',
            'Supervisor en turno',
            'Horometro',
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
