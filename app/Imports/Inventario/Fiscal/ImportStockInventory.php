<?php

namespace App\Imports\Inventario\Fiscal;

use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Stock\InventoryStock;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportStockInventory implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation
{
    use Importable;

    protected $inventory;
    protected $inventoryid;

    public function __construct($inventoryid)
    {
        $this->inventoryid = $inventoryid;
        $this->inventory = $this->getInventoryHead();
    }

    public function model(array $row)
    {
        return new InventoryStock([
            'centerid' => $this->inventory->centerid,
            'warehouseid' => $this->inventory->warehouseid,
            'inventoryid' => $this->inventory->id,
            'locationid' => trim($row['locationid']),
            'partnumberid' => trim($row['partnumberid']),
            'quantity' => floatval($row['quantity']),
            'lpnid' => trim($row['lpnid']) ?? '',
            'unitmeasureid' => trim($row['unitmeasureid']) ?? 'CS',
        ]);
    }

    protected function getInventoryHead(): InventoryHead
    {
        return InventoryHead::where('id', $this->inventoryid)
            ->select(['centerid', 'warehouseid', 'id'])->first();
    }

    // Si el archivo tiene encabezados, empieza a leer desde la segunda fila
    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function rules(): array
    {
        return [
            '*.locationid' => [
                'string',
                'required'
            ],
//            '*.partnumberid' => [
//                'string',
//                'required'
//            ],
            '*.quantity' => [
                'numeric',
                'required'
            ],
            '*.lpnid' => [
                'numeric',
                'required'
            ],
            '*.unitmeasureid' => [
                'string',
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            '*.locationid.required' => 'El campo locationid es obligatorio para cada artículo.',
            '*.locationid.string' => 'El campo locationid debe ser una cadena de texto para cada artículo.',
            '*.partnumberid.required' => 'El campo partnumberid es obligatorio para cada artículo.',
            '*.partnumberid.string' => 'El campo partnumberid debe ser una cadena de texto para cada artículo.',
            '*.unitmeasureid.required' => 'El campo unitmeasureid es obligatorio para cada artículo.',
            '*.unitmeasureid.string' => 'El campo unitmeasureid debe ser una cadena de texto para cada artículo.',
            '*.quantity.required' => 'El campo quantity es obligatorio para cada artículo.',
            '*.quantity.numeric' => 'El campo quantity debe ser numérico para cada artículo.',
            '*.quantity.min' => 'El campo quantity debe ser al menos 1 para cada artículo.',
            '*.lpnid.required' => 'El campo lpnid es obligatorio para cada artículo.',
            '*.lpnid.numeric' => 'El campo lpnid debe ser numérico para cada artículo.',
            '*.lpnid.min' => 'El campo lpnid debe ser al menos 1 para cada artículo.',
        ];
    }

}
