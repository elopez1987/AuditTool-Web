<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryAssignCounterCard;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $errors = InventoryLocationCount::join('inventory_card_counts', 'inventory_location_counts.inventory_card_id', '=', 'inventory_card_counts.id')
            ->join('inventory_cards', 'inventory_card_counts.inventory_card_id', '=', 'inventory_cards.id')
            ->where('inventory_location_counts.inventory_head_id', $inventoryhead->id)
            ->where('inventory_location_counts.statusid_location', 0)
            ->groupBy(
                'inventory_location_counts.inventory_card_id',
                'inventory_location_counts.inventory_head_id',
                'inventory_cards.name',
                'inventory_card_counts.inventory_card_id'
            )
            ->select([
                'inventory_location_counts.inventory_head_id',
                'inventory_location_counts.inventory_card_id',
                'inventory_cards.name as card_name',
                DB::raw('COUNT(inventory_location_counts.statusid_location) as total_errors'),
            ])
            ->get();

        return view(
            'Inventory.InventoryHead.ThirdCount.index',
            compact('inventoryhead', 'errors', 'users')
        );
    }

    public function assignCounter(Request $request, $id)
    {
        $inventoryCardCount = InventoryCardCount::where('id', $id)->first();

        $inventoryCard = InventoryAssignCounterCard::create([
            'inventory_head_id' => $inventoryCardCount->inventory_head_id,
            'inventory_card_count_id' => $inventoryCardCount->id,
            'user_id' => $request->userid,
            'statusid' => 'F',
        ]);

        return $this->redirectBackSuccessMessage('Usuario asignado correctamente');
    }
}
