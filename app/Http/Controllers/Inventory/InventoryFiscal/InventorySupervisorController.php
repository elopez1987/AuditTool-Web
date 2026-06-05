<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryFiscal\InventorySupervisorRequest;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventorySupervisor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventorySupervisorController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $availableUsers = User::where('statusid', 'E')
            ->whereNotIn('id', function ($query) use ($inventoryhead) {
                $query->select('user_id')
                    ->from('inventory_supervisors')
                    ->where('inventory_head_id', $inventoryhead->id)
                    ->union(
                        DB::table('inventory_accountants')
                            ->select('user_id')
                            ->where('inventory_head_id', $inventoryhead->id),
                    );
            })
            ->select('id', 'name')
            ->get();

        return view('Inventory.InventoryHead.Supervisors.index',
            compact('inventoryhead', 'availableUsers'));
    }

    public function addUsersToInventory(InventorySupervisorRequest $request, $id)
    {

        $inventoryHead = InventoryHead::findOrFail($id);
        $inventoryUsers = collect($request->input('user_id', []))
            ->filter()
            ->map(fn ($userId) => (int) $userId)
            ->unique()
            ->values();

        DB::transaction(function () use ($inventoryHead, $inventoryUsers) {
            foreach ($inventoryUsers as $inventoryUserId) {
                InventorySupervisor::firstOrCreate([
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

    public function getAvailableUsersForInventory($id)
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

    public function getSupervisorInventory($id)
    {
        $data = InventorySupervisor::where('inventory_head_id', $id)
            ->with('user:id,name')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', function ($row) {
                return '<a href="javascript:void(0);" onclick="deleteSupervisorInventory(' . $row->id . ')"
                            data-toggle="tooltip" data-placement="top" title="Eliminar Registro">
                            <img src="' . asset('/ico/addestroy1.png') . '" alt="" class="img-16 ml-2">
                        </a>';
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function deleteSupervisorInventory(Request $request, int $id)
    {
        try {
            $record = InventorySupervisor::findOrFail($id);
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
