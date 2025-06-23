<?php

namespace App\Exports\Inventory\FiscalInventory;

use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportInventoryCountTrans02 implements FromCollection, WithHeadings, WithMapping
{
    private $inventoryid;

    use Exportable;

    public function __construct($inventoryid)
    {
        $this->inventoryid = $inventoryid;
    }

    public function headings(): array
    {
        return [
            'C.Distribucion',
            'Bodega',
            'Descripcion',
            'Inventario',
            'Area',
            'Pasillo',
            'Cedula',
            'Ubicacion',
            'Sku',
            'Descripcion',
            'Cantidad',
        ];
    }

    public function collection()
    {
        $data = DB::table('inventoryhead')
            ->select('inventoryhead.centerid', 'inventoryhead.id', 'systemcenterdistributions.name as center',
                'systemwarehouses.name as warehouse', 'inventoryhead.description as descinventario',
                'inventorylocations.areaid', 'inventorylocations.aisleid', 'inventorylocations.cardid',
                'inventorylocations.locationid', 'inventorylocationscan02.partnumberid as partnumber',
                'inventorypartnumbers.lngdsc as description', 'inventorylocationscan02.quantity as quantity', 'inventorylocations.unitmeasureid')
            ->join('systemcenterdistributions','inventoryhead.centerid','=','systemcenterdistributions.centerid')
            ->join('systemwarehouses','inventoryhead.warehouseid','=','systemwarehouses.warehouseid')
            ->leftJoin('inventorylocations','inventoryhead.id','=','inventorylocations.inventoryid')
            ->leftJoin('inventorylocationscan02','inventorylocations.locationid','=','inventorylocationscan02.locationid')
            ->leftJoin('inventorypartnumbers','inventorylocationscan02.partnumberid','=','inventorypartnumbers.prtnum')
            ->where('inventorylocations.type','=','T')
            ->where('inventoryhead.id','=',$this->inventoryid)
            ->get();
        return $data;
    }

    public function map($data): array
    {
        return [
            $data->center,
            $data->warehouse,
            $data->descinventario,
            $data->id,
            $data->areaid,
            $data->aisleid,
            $data->cardid,
            $data->locationid,
            $data->partnumber,
            $data->description,
            $data->quantity,
        ];
    }
}
