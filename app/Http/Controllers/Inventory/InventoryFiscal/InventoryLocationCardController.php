<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryLocationCardController extends BaseController
{
    public function index($id)
    {
        $inventoryCardCount = InventoryCardCount::with('inventoryhead:id,name')->findOrFail($id);

        return view('Inventory.InventoryHead.LocationCard.index',
            compact('inventoryCardCount'));
    }

    public function getLocationCardInventory(Request $request, $id)
    {
        $inventoryCardCount = InventoryCardCount::findOrFail($id);

        $query = DB::table('inventory_location_counts as ilc')
            ->join('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
            ->join('inventory_cards as ic', 'ilc.inventory_card_id', '=', 'ic.id')
            ->join('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->leftJoin('inventorypartnumbers as ipn', 'ilc.inventorypartnumber01_id', '=', 'ipn.id')
            ->leftJoin('inventorypartnumbers as ipn2', 'ilc.inventorypartnumber02_id', '=', 'ipn2.id')
            ->leftJoin('users as u', 'ilc.user_count01_id', '=', 'u.id')
            ->leftJoin('users as u2', 'ilc.user_count02_id', '=', 'u2.id')
            ->leftJoin('systemstatus as s', 'ilc.statusid_count01', '=', 's.statusid')
            ->leftJoin('systemstatus as s2', 'ilc.statusid_count02', '=', 's2.statusid')
            ->where('ilc.inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('ilc.inventory_card_id', $inventoryCardCount->inventory_card_id)
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('il.name', 'like', '%' . $request->name . '%');
            })
            ->select([
                'ilc.id',
                'ia.name as pasillo',
                'ic.name as cedula',
                'il.name as ubicacion',
                'ipn.prtnum as sku01',
                'ipn.lngdsc as description01',
                'ilc.quantity01',
                'u.name as user_count01',
                'ipn2.prtnum as sku02',
                'ipn2.lngdsc as description02',
                'ilc.quantity02',
                'u2.name as user_count02',
                's.name as statusid_count01',
                's2.name as statusid_count02',
                'ilc.statusid_location',
            ]);

        return DataTables::of($query)
            ->addColumn('btn', '')
            ->rawColumns(['btn'])
            ->toJson();
    }
}
