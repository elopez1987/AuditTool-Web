<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryAccountantCardController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::where('inventory_uuid', $id)->first();

        $usersAccountants = InventoryAccountant::where('inventory_head_id', $inventoryhead->id)
            ->leftJoin('users', 'users.id', '=', 'inventory_accountants.user_id')
            ->select(['inventory_accountants.user_id', 'users.name'])
            ->get();

        $cardscount01 = InventoryCardCount::where('inventory_head_id', $inventoryhead->id)
            ->leftJoin('inventory_cards', 'inventory_cards.id', '=', 'inventory_card_counts.inventory_card_id')
            ->select(['inventory_card_counts.id', 'inventory_cards.name'])
            ->whereNull('user_count01_id')->get();

        $cardscount02 = InventoryCardCount::where('inventory_head_id', $inventoryhead->id)
            ->leftJoin('inventory_cards', 'inventory_cards.id', '=', 'inventory_card_counts.inventory_card_id')
            ->select(['inventory_card_counts.id', 'inventory_cards.name'])
            ->whereNull('user_count02_id')->get();

        return view(
            'Inventory.InventoryHead.AccountantCards.index',
            compact('inventoryhead', 'usersAccountants', 'cardscount01', 'cardscount02'),
        );
    }

    public function saveAccountantCards(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        if (is_null($inventoryHead)) {
            return $this->errorResponse('No se encontró el inventario indicado.');
        }

        $userId = $request->input('user_id');
        $cardIds = collect($request->input('inventory_card01_id', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($cardIds->isEmpty()) {
            return $this->errorResponse('No se seleccionaron cédulas para asignar.');
        }

        DB::transaction(function () use ($inventoryHead, $cardIds, $userId) {
            InventoryCardCount::whereIn('id', $cardIds)
                ->where('inventory_head_id', $inventoryHead->id)
                ->update(['user_count01_id' => $userId]);
        });

        return $this->redirectBackSuccessMessage('Conteo asignado con éxito.');
    }

    public function getAccountantCards01(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        $inventoryCardCount = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->with(['inventorycard:id,name', 'user_count01:id,name']);

        return $this->showDataToView($inventoryCardCount, '');
    }

    public function getAccountantCards02(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        $inventoryCardCount = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->with(['inventorycard:id,name', 'user_count01:id,name']);

        return $this->showDataToView($inventoryCardCount, '');
    }
}
