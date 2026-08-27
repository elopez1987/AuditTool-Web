<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryAssignCounterCard;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;

class ThirdCountController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()->where('inventory_uuid', $id)->first();
        if (! $inventoryhead) {
            return $this->errorResponse('El número de inventario no existe');
        }

        $users = InventoryAccountant::where('inventory_head_id', $inventoryhead->id)
            ->join('users', 'inventory_accountants.user_id', '=', 'users.id')
            ->select('inventory_accountants.user_id', 'users.id', 'users.name')
            ->get();

        $errors = InventoryAssignCounterCard::where('inventory_assign_counter_cards.inventory_head_id', $inventoryhead->id)
            ->join('inventory_card_counts', 'inventory_assign_counter_cards.inventory_card_count_id', '=', 'inventory_card_counts.inventory_card_id')
            ->join('inventory_cards', 'inventory_card_counts.inventory_card_id', '=', 'inventory_cards.id')
            ->select([
                'inventory_cards.name as card_name',
                'inventory_assign_counter_cards.statusid',
                'inventory_assign_counter_cards.id',
            ])
            ->where('inventory_assign_counter_cards.statusid', 'F')
            ->get();

        return view(
            'Inventory.InventoryHead.ThirdCount.index',
            compact('inventoryhead', 'users', 'errors')
        );
    }

    public function assignCounter(Request $request, $id)
    {

        InventoryAssignCounterCard::where('id', $id)
            ->update([
                'user_id' => $request->userid,
                'statusid' => 'A',
            ]);

        return $this->redirectBackSuccessMessage('Usuario asignado correctamente');
    }
}
