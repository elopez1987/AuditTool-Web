<?php

namespace App\Exports\Inventory\FiscalInventory;

use App\Models\Inventory\FiscalInventory\Chep\InventoryCountChep;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportReportChepCount implements FromCollection, WithHeadings, WithMapping
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
            'Ubicacion',
            'Cantidad',
        ];
    }

    public function collection()
    {
        $data = InventoryCountChep::where('inventoryid',$this->inventoryid)
            ->select(['inventoryid','locationid','quantity'])->get();
        return $data;
    }

    public function map($data): array
    {
        return [
            $data->inventoryid,
            $data->locationid,
            $data->quantity,
        ];
    }

}
