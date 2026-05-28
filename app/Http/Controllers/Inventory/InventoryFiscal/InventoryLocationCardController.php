<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;

class InventoryLocationCardController extends BaseController
{
    public function index($id)
    {
        $inventoryCardCount = InventoryCardCount::with('inventoryhead:id,name')->findOrFail($id);

        return view('Inventory.InventoryHead.LocationCard.index',
            compact('inventoryCardCount'));
    }
}
