<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAisleCount;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryDashboardController extends BaseController
{
    public function getTotalAisles($id)
    {
        $totalAisles = InventoryAisleCount::where('inventory_head_id', $id)->count();

        return response()->json(['totalAisles' => $totalAisles]);
    }

    public function getTotalCards($id)
    {

        $totalCards = InventoryCardCount::where('inventory_head_id', $id)->count();

        return response()->json(['totalCards' => $totalCards]);
    }

    public function getTotalLocations($id)
    {
        $totalLocations = InventoryLocationCount::where('inventory_head_id', $id)->count();

        return response()->json(['totalLocations' => $totalLocations]);
    }

    public function getTotalErrors($id)
    {
        $totalErrors = InventoryLocationCount::where('inventory_head_id', $id)
            ->where('statusid_location', true)->count();

        return response()->json(['totalErrors' => $totalErrors]);
    }

    public function getDetailAisles(Request $request)
    {
        $data = DB::table('inventory_location_counts as ilc')
            ->leftJoin('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
            ->where('ilc.inventory_head_id', $request->inventory_head_id)
            ->groupBy('ia.name')
            ->orderBy('ia.name')
            ->selectRaw("
					ia.name                                                           AS pasillo,
					COUNT(DISTINCT ilc.inventory_card_id)                            AS total_cedulas,
					COUNT(ilc.id)                                                     AS total_ubicaciones,
					SUM(CASE WHEN ilc.statusid_count01 = 'F' THEN 1 ELSE 0 END)     AS pendiente_count01,
					SUM(CASE WHEN ilc.statusid_count01 = 'C' THEN 1 ELSE 0 END)     AS completado_count01,
					SUM(CASE WHEN ilc.statusid_count02 = 'F' THEN 1 ELSE 0 END)     AS pendiente_count02,
					SUM(CASE WHEN ilc.statusid_count02 = 'C' THEN 1 ELSE 0 END)     AS completado_count02,
					CAST(
						CASE WHEN COUNT(ilc.id) > 0
							THEN (SUM(CASE WHEN ilc.statusid_count01 = 'C' THEN 1 ELSE 0 END) * 100.0) / COUNT(ilc.id)
							ELSE 0
						END AS DECIMAL(5,2)
					)                                                                 AS avance_count01,
					CAST(
						CASE WHEN COUNT(ilc.id) > 0
							THEN (SUM(CASE WHEN ilc.statusid_count02 = 'C' THEN 1 ELSE 0 END) * 100.0) / COUNT(ilc.id)
							ELSE 0
						END AS DECIMAL(5,2)
					)                                                                 AS avance_count02
				")
            ->get();

        return $this->showDataToView($data, '');
    }

    public function getDetailInventoryErrors(Request $request)
    {
        $data = DB::table('inventory_location_counts as ilc')
            ->join('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
            ->join('inventory_cards as ic', 'ilc.inventory_card_id', '=', 'ic.id')
            ->join('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->where('ilc.inventory_head_id', $request->inventory_head_id)
            ->where('ilc.statusid_location', 0)
            ->select([
                'ia.name as pasillo',
                'ic.name as cedula',
                'il.name as location',
            ])
            ->get();

        return $this->showDataToView($data, '');
    }

    public function getTotalCount01($id)
    {
        $toalCount01 = InventoryLocationCount::where('inventory_head_id', $id)
            ->where('statusid_count01', 'C')
            ->count();

        return response()->json(['totalCount01' => $toalCount01]);
    }

    public function getTotalCount02($id)
    {
        //        DB::connection()->enableQueryLog();
        $toalCount02 = InventoryLocationCount::where('inventory_head_id', $id)
            ->where('statusid_count02', 'C')
            ->count();
        //        $queries = DB::getQueryLog();

        return response()->json(['totalCount02' => $toalCount02]);
    }
}
