<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryLocationCountsController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::where('inventory_uuid', $id)->first();

        return view('Inventory.InventoryHead.InventoryLocationCounts.index',
            compact('inventoryHead'));
    }

    public function getLocationCountsInventory(Request $request, $id)
    {
        $inventoryHead = InventoryHead::where('id', $id)->first();

        $data = DB::table('inventory_location_counts as ilc')
            ->select([
                'ilc.id',
                'ia.name as aisle_name',
                'ic.name as card_name',
                'il.name as location_name',
                'iv1.prtnum as part_number1',
                'iv1.lngdsc as description01',
                'ilc.quantity01',
                'iv2.prtnum as part_number2',
                'iv2.lngdsc as description02',
                'ilc.quantity02',
                'ilc.statusid_location',
                'im.unitmeasureid',
            ])
            ->leftJoin('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
            ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
            ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->join('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->leftJoin('inventorypartnumbers as iv1', 'ilc.inventorypartnumber01_id', '=', 'iv1.id')
            ->leftJoin('inventorypartnumbers as iv2', 'ilc.inventorypartnumber02_id', '=', 'iv2.id')
            ->leftJoin('inventoryunitsmeasure as im', 'il.inventoryunitsmeasure_id', '=', 'im.id')
            ->when($request->filled('aislename'), function ($q) use ($request) {
                $q->where('ia.name', 'like', '%' . $request->aislename . '%');
            })
            ->when($request->filled('cardname'), function ($q) use ($request) {
                $q->where('ic.name', 'like', '%' . $request->cardname . '%');
            })
            ->when($request->filled('locationname'), function ($q) use ($request) {
                $q->where('il.name', 'like', '%' . $request->locationname . '%');
            })
            ->where('ilc.inventory_head_id', $inventoryHead->id);

        return DataTables::of($data)

            ->addColumn('btn', '')
            ->rawColumns(['btn'])
            ->toJson();

    }
}
