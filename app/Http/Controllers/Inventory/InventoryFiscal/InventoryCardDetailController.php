<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryCardDetailController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::where('inventory_uuid', $id)->first();

        return view('Inventory.InventoryHead.CardDetail.index',
            ['inventoryHead' => $inventoryHead]);
    }

    public function getCardsInventory(Request $request, $id)
    {
        $query = DB::table('inventory_card_counts as icc')
            ->join('inventory_aisles as ia', 'icc.inventory_aisle_id', '=', 'ia.id')
            ->join('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->leftJoin('users as u', 'icc.user_count01_id', '=', 'u.id')
            ->leftJoin('users as u2', 'icc.user_count02_id', '=', 'u2.id')
            ->leftJoin('systemstatus as s', 'icc.statusid_count01', '=', 's.statusid')
            ->leftJoin('systemstatus as s2', 'icc.statusid_count02', '=', 's2.statusid')
            ->where('icc.inventory_head_id', $id)
            ->when($request->input('name'), function ($q) use ($request) {
                $q->where('ia.name', 'like', '%' . $request->input('name') . '%');
            })
            ->when($request->input('cardname'), function ($q) use ($request) {
                $q->where('ic.name', 'like', '%' . $request->input('cardname') . '%');
            })
            ->select([
                'icc.id',
                'icc.inventory_head_id',
                'icc.statusid_count01 as statusid01',
                'icc.statusid_count02 as statusid02',
                'ia.name as pasillo',
                'ic.name as cedula',
                'u.name as user_count01',
                'u2.name as user_count02',
                's.name as statusid_count01',
                's2.name as statusid_count02',
                'icc.key_user_count_01',
                'icc.key_user_count_02',
                's.auxiliary01 as color_count01',
                's2.auxiliary01 as color_count02',
            ]);

        return DataTables::of($query)
            ->addColumn('btn', function ($row) {
                return view('Inventory.InventoryHead.CardDetail.buttons', ['row' => $row])->render();
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function destroy($id)
    {
        try {

            $inventoryCard = DB::table('inventory_card_counts')->where('id', $id)->first();

            DB::table('inventory_location_counts')->where('inventory_card_id', $inventoryCard->inventory_card_id)->delete();
            DB::table('inventory_card_counts')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Cédula eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}
