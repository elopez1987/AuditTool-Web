<?php

namespace App\Exports\Shipment\Events;

use App\Models\Shipments\ViewShipmentEvent;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportShipmentEventsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    use Exportable;
    private $workdayin;
    private $workdayout;

    public function __construct($workdayin, $workdayout)
    {
        $this->workdayin = $workdayin;
        $this->workdayout = $workdayout;
    }

    public function headings(): array
    {
        return [
            'Bodega',
            'Fecha',
            'Turno',
            'Embarque',
            'Cliente',
            'Destino',
            'Confirmación',
            'Linea de transporte',
            'Placa de caja',
            'Placa de tracto',
            'Operador',
            'Total pick',
            'Total pallets',
            '% de avance',
            'Cita con cliente',
            'Cita de carga',
            'F. entrada de unidad',
            'Llegada a mesa de control',
            'Lanzamiento de ola',
            'Preparado en piso',
            'Por cargar',
            'Inicio de carga',
            'Fin de carga',
            'Envio de doc. a facturación',
            'Inicio de facturación',
            'Fin de facturación',
            'Autorización de salida',
            'Salida de unidad',
            'Maximo de salida calculada',
            'Estatus de llegada',
            'Estatus de salida',
            'Tiempo de trayecto ( Horas )',
            'Horas restantes al salir',
            'Estadia en patio',
            'Estatus',
        ];
    }

    public function collection()
    {
        return ViewShipmentEvent::controlAccessUser()
            ->whereBetween('workday', [$this->workdayin, $this->workdayout])
            ->get();
    }

    public function map($shipments): array
    {
        return [
            $shipments->bodega,
            $shipments->workday,
            $shipments->turno,
            $shipments->embarque,
            $shipments->cliente,
            $shipments->destino,
            $shipments->confirmation,
            $shipments->linea,
            $shipments->boxnumber,
            $shipments->trucknumber,
            $shipments->operatorname,
            $shipments->pick,
            $shipments->pallets,
            $shipments->avance,
            $shipments->fcitaconcliente,
            $shipments->fcarga,
            $shipments->fentrada,
            $shipments->llegadaamesa,
            $shipments->lanzamientoola,
            $shipments->preparadoenpiso,
            $shipments->porcargar,
            $shipments->iniciacarga,
            $shipments->fincarga,
            $shipments->enviodocumentos,
            $shipments->iniciofacturacion,
            $shipments->finfacturacion,
            $shipments->autorizasalida,
            $shipments->salidadeunidad,
            $shipments->maxsalida,
            $shipments->estatusdearribo,
            $shipments->estatusdedespacho,
            $shipments->horastrayecto,
            $shipments->horasrestante,
            $shipments->horasenpatio,
            $shipments->estatus,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AI1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
