<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;

class InventoryAccountantCardController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::where('inventory_uuid', $id)->first();
        $usersAccountants = InventoryAccountant::where('inventory_head_id', $inventoryhead->id)
            ->leftJoin('users', 'users.id', '=', 'inventory_accountants.user_id')
            ->select(['inventory_accountants.user_id', 'users.name'])
            ->get();

        $cards = InventoryCardCount::where('inventory_head_id', $inventoryhead->id)
            ->leftJoin('inventory_cards', 'inventory_cards.id', '=', 'inventory_card_counts.inventory_card_id')
            ->select(['inventory_card_counts.id', 'inventory_cards.name'])
            ->whereNull('user_count01_id')->get();

        return view('Inventory.InventoryHead.AccountantCards.index',
            compact('inventoryhead', 'usersAccountants', 'cards'));
    }
}
