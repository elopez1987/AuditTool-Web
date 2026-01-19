<?php

namespace App\Exports\Shipment\Events;

use App\Models\Shipments\OrderDeliverie;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportShipmentEventsExport implements FromCollection, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
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
        return OrderDeliverie::select([
            'od.centerid',
            'od.warehouseid',
            'od.workday',
            'sw.name as bodega',
            'st.name as turno',
            'se.name as estatus',
            'od.shipmentid as embarque',
            'oc.name as cliente',
            'oc.city as destino',
            'od.confirmation',
            'sc.name as linea',
            'ru.boxnumber',
            'ru.trucknumber',
            'ru.operatorname',
            'sh.totalpieces as pick',
            'sh.totalcontainers as pallets',
            'sd.auxiliary02 as avance',
            'od.downloaddate as fcitaconcliente',
            DB::raw('DATEADD(minute, 30, od.loadingdate) as fcarga'),
            'ru.entrydate as fentrada',
            'ru.deliverdate as llegadaamesa',
            'sd.startshipment as lanzamientoola',
            'sd.endchecker as preparadoenpiso',
            'sch.startdate as inicioverificacion',
            'sch.finishdate as finverificacion',
            'sd.forload as porcargar',
            'sd.startload as iniciacarga',
            'sd.endload as fincarga',
            'sd.senddocument as enviodocumentos',
            'sh.startgoodissuedate as iniciofacturacion',
            'sh.goodissuedate as finfacturacion',
            'sd.requestletter as solicitudcartaporte',
            'sd.responseletter as respuestacartaporte',
            'ru.autorizaoutputdate as autorizasalida',
            'om.outputdate as maxsalida',
            'ru.outputdate as salidadeunidad',
            DB::raw("IIF(ru.entrydate IS NULL, 'Sin arribo', IIF(DATEADD(minute, 30, od.loadingdate) > ru.entrydate, 'En tiempo', 'Fuera de tiempo')) as estatusdearribo"),
            DB::raw("IIF(ru.outputdate IS NULL, 'Sin despacho', IIF(ru.outputdate > om.outputdate, 'Fuera de tiempo', 'En tiempo ')) as estatusdedespacho"),
            DB::raw('datediff(HOUR, ru.outputdate, od.downloaddate) as horasrestante'),
            DB::raw('oc.timetransfer / 60 as horastrayecto'),
            DB::raw('IIF(ru.entrydate IS NULL, 0, IIF(ru.outputdate IS NULL, datediff(HOUR, ru.entrydate, getdate()), datediff(HOUR, ru.entrydate, ru.outputdate))) as horasenpatio'),
        ])
            ->from('orderdeliveries as od')
            ->leftJoin('shipmentheader as sh', 'od.id', '=', 'sh.orderid')
            ->leftJoin('ordercustomers as oc', 'od.customerid', '=', 'oc.id')
            ->leftJoin('storageturns as st', 'od.turnid', '=', 'st.id')
            ->leftJoin('shipmentdataoperation as sd', 'od.id', '=', 'sd.orderid')
            ->leftJoin('systemstatus as se', 'od.statusid', '=', 'se.statusid')
            ->leftJoin('receiptunits as ru', 'od.folionumber', '=', 'ru.id')
            ->leftJoin('ordermonitor as om', 'od.id', '=', 'om.orderid')
            ->leftJoin('shipmentcarriers as sc', 'od.carrierid', '=', 'sc.id')
            ->leftJoin('systemwarehouses as sw', 'od.warehouseid', '=', 'sw.warehouseid')
            ->leftJoin('shipmentcheck as sch', function ($join) {
                $join->on('od.id', '=', 'sch.orderid')
                    ->on('od.warehouseid', '=', 'sch.warehouseid');
            })
            ->where('od.centerid', $this->request->centerid)
            ->where('od.warehouseid', $this->request->warehouseid)
            ->whereBetween('od.workday', [$this->request->workdayin, $this->request->workdayout])
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
