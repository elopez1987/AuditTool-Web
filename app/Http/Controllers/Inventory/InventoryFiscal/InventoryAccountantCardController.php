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

        $usersAccountants02 = $usersAccountants;

        $cardscount01 = collect();
        $cardscount02 = collect();

        return view(
            'Inventory.InventoryHead.AccountantCards.index',
            compact('inventoryhead', 'usersAccountants', 'usersAccountants02', 'cardscount01', 'cardscount02'),
        );
    }

    /**
     * Cedulas disponibles para asignar el conteo 1 al usuario indicado:
     * sin usuario asignado en el conteo 1, y sin usuario asignado en el
     * conteo 2 o, si ya tiene, que sea distinto al usuario seleccionado
     * (un mismo contador no puede hacer ambos conteos de una cedula).
     */
    public function getCardForCount01(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        if (is_null($inventoryHead)) {
            return response()->json([]);
        }

        $userId = $request->input('user_id');

        $cardscount01 = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->whereNull('user_count01_id')
            ->where(function ($query) use ($userId) {
                $query->whereNull('user_count02_id')
                    ->orWhere('user_count02_id', '!=', $userId);
            })
            ->leftJoin('inventory_cards', 'inventory_cards.id', '=', 'inventory_card_counts.inventory_card_id')
            ->select(['inventory_card_counts.id', 'inventory_cards.name'])
            ->get();

        return response()->json($cardscount01);
    }

    /**
     * Cedulas disponibles para asignar el conteo 2 al usuario indicado:
     * sin usuario asignado en el conteo 2, y sin usuario asignado en el
     * conteo 1 o, si ya tiene, que sea distinto al usuario seleccionado
     * (un mismo contador no puede hacer ambos conteos de una cedula).
     */
    public function getCardForCount02(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        if (is_null($inventoryHead)) {
            return response()->json([]);
        }

        $userId = $request->input('user_id');

        $cardscount02 = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->whereNull('user_count02_id')
            ->where(function ($query) use ($userId) {
                $query->whereNull('user_count01_id')
                    ->orWhere('user_count01_id', '!=', $userId);
            })
            ->leftJoin('inventory_cards', 'inventory_cards.id', '=', 'inventory_card_counts.inventory_card_id')
            ->select(['inventory_card_counts.id', 'inventory_cards.name'])
            ->get();

        return response()->json($cardscount02);
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

    public function saveAccountantCards02(Request $request, $id)
    {
        $inventoryHead = InventoryHead::find($id);

        if (is_null($inventoryHead)) {
            return $this->errorResponse('No se encontró el inventario indicado.');
        }

        $userId = $request->input('user_id_02');
        $cardIds = collect($request->input('inventory_card02_id', []))
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
                ->update(['user_count02_id' => $userId]);
        });

        return $this->redirectBackSuccessMessage('Conteo asignado con éxito.');
    }

    public function getAccountantCards01($id)
    {
        $inventoryHead = InventoryHead::find($id);

        $inventoryCardCount = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->with(['inventorycard:id,name', 'user_count01:id,name'])
            ->whereNotNull('user_count01_id');

        return $this->showDataToView($inventoryCardCount, '');
    }

    public function getAccountantCards02($id)
    {
        $inventoryHead = InventoryHead::find($id);

        $inventoryCardCount = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->with(['inventorycard:id,name', 'user_count02:id,name'])
            ->whereNotNull('user_count02_id');

        return $this->showDataToView($inventoryCardCount, '');
    }
}
