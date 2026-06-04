<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;

class InventoryHeadEventController extends BaseController
{
    public function activateInventoryHead($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryHead->update(['statusid' => 'INF-02']);

        return $this->redirectBackSuccessMessage('Inventario activado con exito.');
    }

    public function closeInventoryHead($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryHead->update(['statusid' => 'INF-03']);

        return $this->redirectBackSuccessMessage('Inventario cerrado con exito.');
    }

    public function deleteInventoryHead($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryHead->delete();

        return $this->redirectBackSuccessMessage('Inventario eliminado con exito.');
    }

    public function initializeInventory($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);

        InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->update([
                'statusid_count01' => 'F',
                'statusid_count02' => 'F',
                'key_user_count_01' => null,
                'key_user_count_02' => null,
            ]);

        InventoryLocationCount::where('inventory_head_id', $inventoryHead->id)
            ->update([
                'inventorypartnumber01_id' => null,
                'inventorypartnumber02_id' => null,
                'quantity01' => null,
                'quantity02' => null,
                'user_count01_id' => null,
                'user_count02_id' => null,
                'statusid_count01' => 'F',
                'statusid_count02' => 'F',
                'statusid_location' => null,
            ]);

        return $this->redirectBackSuccessMessage('Inventario inicializado con exito.');
    }
}
