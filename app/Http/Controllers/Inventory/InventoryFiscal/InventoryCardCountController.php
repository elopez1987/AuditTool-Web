<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryCardCountRequest;
use App\Models\Inventory\InventoryFiscal\InventoryAisleCount;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryCardCountController extends BaseController
{
    public function getCardCountInventory($id)
    {
        $data = InventoryCardCount::where('inventory_head_id', $id)
            ->with('inventorycard:id,name')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', function ($row) {
                return '<a href="javascript:void(0);" onclick="deleteCardCountFromInventory(' . $row->id . ')"
                            data-toggle="tooltip" data-placement="top" title="Eliminar Registro">
                            <img src="' . asset('/ico/addestroy1.png') . '" alt="" class="img-16 ml-2">
                        </a>';
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function getAvailableCardsForInventory($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $aislesPlanning = InventoryAisleCount::where('inventory_head_id', $inventoryHead->id)
            ->pluck('inventory_aisle_id');

        $assignedCardIds = InventoryCardCount::where('inventory_head_id', $inventoryHead->id)
            ->pluck('inventory_card_id');

        $cards = InventoryCard::controlAccessUser()
            ->where('statusid', 'E')
            ->whereIn('inventory_aisle_id', $aislesPlanning)
            ->whereNotIn('id', $assignedCardIds)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards,
        ]);
    }

    public function addCardCountToInventory(InventoryCardCountRequest $request, $id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryCardIds = collect($request->input('inventory_card_id', []))
            ->filter()
            ->map(fn ($cardId) => (int) $cardId)
            ->unique()
            ->values();

        DB::transaction(function () use ($inventoryHead, $inventoryCardIds) {
            foreach ($inventoryCardIds as $inventoryCardId) {
                $inventoryCard = InventoryCard::findOrFail($inventoryCardId);
                InventoryCardCount::firstOrCreate([
                    'inventory_head_id' => $inventoryHead->id,
                    'inventory_card_id' => $inventoryCard->id,
                    'inventory_aisle_id' => $inventoryCard->inventory_aisle_id,
                ]);
            }
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cédulas asignadas al inventario con éxito!!',
            ]);
        }

        return $this->redirectBackSuccessMessage('Cédulas asignadas al inventario con éxito!!');

    }

    public function deleteCardCountFromInventory(Request $request, int $id)
    {
        try {
            $record = InventoryCardCount::findOrFail($id);
            $record->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cédula eliminada del conteo con éxito!!',
                ]);
            }

            return $this->redirectBackSuccessMessage('Cédula eliminada del conteo con éxito!!');
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cédula: ' . $th->getMessage(),
            ], 500);
        }
    }
}
