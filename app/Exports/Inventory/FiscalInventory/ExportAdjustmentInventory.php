<?php

namespace App\Exports\Inventory\FiscalInventory;

use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationScan03;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportAdjustmentInventory implements FromCollection, WithHeadings, WithMapping
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
            'Cedula',
            'Ubicacion',
            'Sku 01',
            'Cantidad 01',
            'Sku 02',
            'Cantidad 02',
            'Tipo',
            'Usuario',
            'F.Movimiento',
        ];
    }

    public function collection()
    {
        $data = InventoryLocationScan03::where('inventoryid',$this->inventoryid)
            ->leftJoin('users','inventorylocationscan03.createuser','=','users.username')
            ->select([
                'inventorylocationscan03.*',
                'users.name'
            ])
            ->get();
        return $data;
    }

    public function map($data): array
    {
        return [
            $data->inventoryid,
            $data->cardid,
            $data->locationid,
            $data->partnumberid01,
            $data->quantity01,
            $data->partnumberid02,
            $data->quantity02,
            $data->type,
            $data->name,
            $data->created_at
        ];
    }

}
