<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class InventoryAisleDetailController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::findOrFail($id);

        return view('Inventory.InventoryHead.AisleDetail.index',
            compact('inventoryHead'));
    }

    public function getAislesInventory(Request $request, $id)
    {
        $query = DB::table('inventory_aisle_counts as iac')
            ->join('inventory_aisles as ia', 'iac.inventory_aisle_id', '=', 'ia.id')
            ->join('systemwarehouses as sw', 'ia.warehouseid', '=', 'sw.warehouseid')
            ->join('systemcenterdistributions as scd', 'sw.centerid', '=', 'scd.centerid')
            ->where('iac.inventory_head_id', $id)
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('ia.name', 'like', '%' . $request->name . '%');
            })
            ->select([
                'sw.name as warehouse',
                'scd.description as center',
                'iac.id',
                'iac.inventory_head_id',
                'ia.name as pasillo',
            ]);

        return DataTables::of($query)
            ->addColumn('btn', function ($row) {
                $btn = '';

                if (Gate::allows('inventory_aisle_counts.delete')) {
                    $btn .= '<button type="button"
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="' . $row->id . '">
                        <i class="mdi mdi-delete"></i> Eliminar
                    </button>';
                }

                return $btn;
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function destroy($id)
    {
        try {

            $inventoryAisle = DB::table('inventory_aisle_counts')->where('id', $id)->first();

            DB::table('inventory_location_counts')->where('inventory_aisle_id', $inventoryAisle->inventory_aisle_id)->delete();
            DB::table('inventory_card_counts')->where('inventory_aisle_id', $inventoryAisle->inventory_aisle_id)->delete();
            DB::table('inventory_aisle_counts')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Pasillo eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}
