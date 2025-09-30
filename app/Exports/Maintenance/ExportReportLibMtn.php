<?php

namespace App\Exports\Maintenance;

use App\Models\Maintenance\StorageCheckListActiveTruck;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportReportLibMtn implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
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
        return StorageCheckListActiveTruck::select([
            'storagechecklistactivetruck.checklisttruckid',
            'storagechecklistactivetruck.serialnumber',
            'storagechecklistslifttrucks.name as tipocheck',
            'storagechecklistdetaillifttrucks.description as detalle',
            'users.name as liberador',
            'storagechecklistactivetruck.created_at',
        ])
            ->leftJoin('storagechecklistslifttrucks', 'storagechecklistactivetruck.checklistid', '=', 'storagechecklistslifttrucks.id')
            ->leftJoin('storagechecklistdetaillifttrucks', 'storagechecklistactivetruck.checklistdetailid', '=', 'storagechecklistdetaillifttrucks.id')
            ->leftJoin('users', 'storagechecklistactivetruck.username', '=', 'users.username')
            ->whereBetween('storagechecklistactivetruck.created_at', [$this->workdayin, $this->workdayout])
            ->where('storagechecklistactivetruck.centerid', $this->centerid)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Número de checklist',
            'Número de serie',
            'Tipo de equipo',
            'Detalle del check list',
            'Especialista',
            'Fecha de creación',
        ];
    }

    public function map($items): array
    {
        return [
            $items->checklisttruckid,
            $items->serialnumber,
            $items->tipocheck,
            $items->detalle,
            $items->liberador,
            $items->created_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
