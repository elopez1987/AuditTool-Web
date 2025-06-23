<?php

namespace App\Exports\Inventory\FiscalInventory;

use App\Models\Inventory\FiscalInventory\Locations\MonitorLocationInventory;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportaInventoryCount implements FromCollection, WithHeadings, WithMapping
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
            'Inventario',
            'Area',
            'Pasillo',
            'Cedula',
            'Ubicacion',
            'Sku',
            'Descripcion',
            'Cantidad 01',
            'Lpns 01',
            'Restos 01',
            'Total 01',

            'Sku',
            'Descripcion',
            'Cantidad 02',
            'Lpns 02',
            'Restos 02',
            'Total 02',
        ];
    }

    public function collection()
    {
        $data = MonitorLocationInventory::where('id','=',$this->inventoryid)->get();
        return $data;
    }


    public function map($data): array
    {
        return [
            $data->id,
            $data->areaid,
            $data->aisleid,
            $data->cardid,
            $data->locationid,

            $data->partnumber01,
            $data->description01,
            $data->quantity01,
            $data->lpns01,
            $data->over01,
            $data->totalcount01,

            $data->partnumber02,
            $data->description02,
            $data->quantity02,
            $data->lpns02,
            $data->over02,
            $data->totalcount02,


        ];
    }



}
