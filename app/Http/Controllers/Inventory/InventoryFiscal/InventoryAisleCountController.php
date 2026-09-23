<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryAisleCountRequest;
use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use App\Models\Inventory\InventoryFiscal\InventoryAisleCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class InventoryAisleCountController extends BaseController
{
    public function getAvailableAislesForInventory($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);

        $aisles = InventoryAisle::controlAccessUser()
            ->where('statusid', 'E')
            ->whereNotIn('id', InventoryAisleCount::query()
                ->select('inventory_aisle_id')
                ->where('inventory_head_id', $inventoryHead->id)
            )
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $aisles,
        ]);
    }

    public function getAisleCountInventory($id)
    {
        $data = InventoryAisleCount::where('inventory_head_id', $id)
            ->with('inventoryaisle:id,name')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', function ($row) {
                return '<a href="javascript:void(0);" onclick="deleteAisleCountFromInventory(' . $row->id . ')"
                            data-toggle="tooltip" data-placement="top" title="Eliminar Registro">
                            <img src="' . asset('/ico/addestroy1.png') . '" alt="" class="img-16 ml-2">
                        </a>';
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function addAisleCountToInventory(InventoryAisleCountRequest $request, $id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryAisleIds = collect($request->input('inventory_aisle_id', []))
            ->filter()
            ->map(fn ($aisleId) => (int) $aisleId)
            ->unique()
            ->values();

        DB::transaction(function () use ($inventoryHead, $inventoryAisleIds) {
            foreach ($inventoryAisleIds as $inventoryAisleId) {
                InventoryAisleCount::firstOrCreate([
                    'inventory_head_id' => $inventoryHead->id,
                    'inventory_aisle_id' => $inventoryAisleId,
                ]);
            }
        });
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pasillos asignados al inventario con éxito!!',
            ]);
        }

        return $this->redirectBackSuccessMessage('Pasillos asignados al inventario con éxito!!');
    }

    public function deleteAisleCountFromInventory(Request $request, int $id)
    {
        try {
            $record = InventoryAisleCount::findOrFail($id);
            $record->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pasillo eliminado del conteo con éxito!!',
                ]);
            }
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el pasillo: ' . $th->getMessage(),
            ], 500);
        }
    }
}
