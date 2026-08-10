<?php

namespace App\Exports\Yard\ReceiptUnit;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Exporta la totalidad de los registros de existencia de cajas que cumplen
 * con los filtros aplicados en Yards.ReceiptUnits.index (pantalla principal
 * y modal Yards.ReceiptUnits.filters), sin limitarse a lo que el datatable
 * muestra paginado en pantalla.
 */
class ExportAllReceiptUnits implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'C.Distribución',
            'Bodega',
            'Folio',
            'Embarque/Recibo',
            'C.Barra',
            'Linea de transporte',
            'Placas de caja',
            'Número de caja',
            'Placas de tracto',
            'Nombre del operador',
            'Celular',
            'Zona',
            'Ubicación',
            'E. de Caja',
            'Estatus',
            'Tipo de Caja',
            'Categoria',
            'Concepto',
            'F.Ingreso',
            'Origen',
            'Estadia',
            'Folio de tracto',
        ];
    }

    public function collection()
    {
        return ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,carrierid,name', 'zone:id,name', 'location:id,name', 'event:id,name',
                'tracto:id,operatorname,phone,trucknumber,econumber', 'center:centerid,description,name', 'warehouse:warehouseid,name',
                'unittype:id,description', 'truckcategory:truckcategoryid,description',
                'unitstatus:id,name', 'status:statusid,name', 'truck:id,name'])
            ->select([
                'receiptunits.id',
                'receiptunits.centerid',
                'receiptunits.warehouseid',
                'receiptunits.barcode',
                'receiptunits.shipmentid',
                'receiptunits.carrierid',
                'receiptunits.truckid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.entrydate',
                'receiptunits.zoneid',
                'receiptunits.locationid',
                'receiptunits.unitstatusid',
                'receiptunits.razonstatusid',
                'receiptunits.unittypeid',
                'receiptunits.truckcategoryid',
                'receiptunits.foliotruck',
                'receiptunits.statusid',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR', 'ES'])
            ->Folio($this->request->input('folionumber'))
            ->Barcode($this->request->input('barcode'))
            ->Location($this->request->input('location'))
            ->BoxNumber($this->request->input('boxnumber'))
            ->EcoNumber($this->request->input('econumber'))
            ->TruckNumber($this->request->input('trucknumber'))
            ->Rason($this->request->input('rasonid'))
            ->Carrier($this->request->input('carrierid'))
            ->Warehouse($this->request->input('warehouseid'))
            ->Shipment($this->request->input('shipmentid'))
            ->Status($this->request->input('statusid'))
            ->UnitStatus($this->request->input('unitstatusid'))
            ->orderByDesc('receiptunits.id')
            ->get();
    }

    public function map($unit): array
    {
        return [
            $unit->center->description ?? '',
            $unit->warehouse->name ?? '',
            $unit->id,
            $unit->shipmentid,
            $unit->barcode,
            $unit->carrier->name ?? '',
            $unit->boxnumber,
            $unit->econumber,
            $unit->tracto->trucknumber ?? '',
            $unit->tracto->operatorname ?? '',
            $unit->tracto->phone ?? '',
            $unit->zone->name ?? '',
            $unit->location->name ?? '',
            $unit->unitstatus->name ?? '',
            $unit->status->name ?? '',
            $unit->truckcategory->description ?? '',
            $unit->truck->name ?? '',
            $unit->event->name ?? '',
            $unit->entrydate,
            $unit->unittype->description ?? '',
            $unit->staytime,
            $unit->tracto->id ?? '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:V1')->getFill()
                    ->applyFromArray(['fillType' => Fill::FILL_SOLID,
                        'rotation' => 0, 'startColor' => ['rgb' => '64b5f6'],
                    ]);
            },
        ];
    }
}
