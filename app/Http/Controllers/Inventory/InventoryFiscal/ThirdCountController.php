<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Support\Facades\DB;

class ThirdCountController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()->where('inventory_uuid', $id)->first();
        if (! $inventoryhead) {
            return $this->errorResponse('El número de inventario no existe');
        }

        $errors = DB::table('inventory_location_counts as ilc')
            ->select([
                'inventory_head_id',
                'ilc.inventory_card_id',
                'ic.name as cardid',
                DB::raw('COUNT(statusid_location) as total_errors'),
            ])
            ->join('inventory_cards as ic', 'ilc.inventory_card_id', '=', 'ic.id')
            ->where('inventory_head_id', $inventoryhead->id)
            ->where('statusid_location', 0)
            ->groupBy('ilc.inventory_card_id', 'ilc.inventory_aisle_id', 'inventory_head_id', 'ic.name')
            ->get();

        return view('Inventory.InventoryHead.ThirdCount.index',
            compact('inventoryhead', 'errors'));
    }
}
