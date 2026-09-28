<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryAccountantRequest;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryAccountantController extends BaseController
{
    public function addUsersAccountants(InventoryAccountantRequest $request, $id)
    {

        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryUsers = collect($request->input('user_id', []))
            ->filter()
            ->map(fn ($userId) => (int) $userId)
            ->unique()
            ->values();

        DB::transaction(function () use ($inventoryHead, $inventoryUsers) {
            foreach ($inventoryUsers as $inventoryUserId) {
                InventoryAccountant::firstOrCreate([
                    'inventory_head_id' => $inventoryHead->id,
                    'user_id' => $inventoryUserId,
                ]);
            }
        });
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pasillos asignados al inventario con éxito!!',
            ]);
        }
    }

    public function getAvailableAccountantsForInventory($id)
    {
        $users = User::where('statusid', 'E')
            ->whereNotIn('id', function ($query) use ($id) {
                $query->select('user_id')
                    ->from('inventory_supervisors')
                    ->where('inventory_head_id', $id)
                    ->union(
                        DB::table('inventory_accountants')
                            ->select('user_id')
                            ->where('inventory_head_id', $id),
                    );
            })
            ->select('id', 'name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function getAccountantsInventory($id)
    {
        $data = InventoryAccountant::where('inventory_head_id', $id)
            ->with('user:id,name')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', function ($row) {
                return '<a href="javascript:void(0);" onclick="deleteAccountantsInventory(' . $row->id . ')"
                            data-toggle="tooltip" data-placement="top" title="Eliminar Registro">
                            <img src="' . asset('/ico/addestroy1.png') . '" alt="" class="img-16 ml-2">
                        </a>';
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function deleteAccountantsInventory(Request $request, int $id)
    {
        try {
            $record = InventoryAccountant::findOrFail($id);
            $record->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pasillo eliminado del conteo con éxito!!',
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el pasillo: ' . $th->getMessage(),
            ], 500);
        }
    }
}
