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

        return view(
            'Inventory.InventoryHead.InventoryLocationCounts.index',
            compact('inventoryHead'),
        );
    }

    public function getLocationCountsInventory(Request $request, $id)
    {
        $inventoryHead = InventoryHead::where('id', $id)->firstOrFail();

        $data = $this->baseLocationCountsQuery($request, $inventoryHead);

        return DataTables::of($data)
            ->addColumn('btn', '')
            ->rawColumns(['btn'])
            ->toJson();

    }

    public function getLocationCountsKpis(Request $request, $id)
    {
        $inventoryHead = InventoryHead::where('id', $id)->firstOrFail();

        // Se reutiliza la misma consulta del datatable como subconsulta para agregar los totales por estatus.
        $totals = DB::query()
            ->fromSub($this->baseLocationCountsQuery($request, $inventoryHead), 'resumen')
            ->selectRaw("
				COUNT(*) as total_ubicaciones,
				SUM(CASE WHEN status_comparacion = 'OK' THEN 1 ELSE 0 END) as total_ok,
				SUM(CASE WHEN status_comparacion = 'ERROR' THEN 1 ELSE 0 END) as total_error,
				SUM(CASE WHEN status_comparacion = 'PENDIENTE' THEN 1 ELSE 0 END) as total_pendiente
			")
            ->first();

        $totalLocations = (int) ($totals->total_ubicaciones ?? 0);
        $totalOk = (int) ($totals->total_ok ?? 0);

        return response()->json([
            'totalUbicaciones' => $totalLocations,
            'totalOk' => $totalOk,
            'totalError' => (int) ($totals->total_error ?? 0),
            'totalPendiente' => (int) ($totals->total_pendiente ?? 0),
            'porcentajeAcertividad' => $totalLocations > 0
                ? round(($totalOk / $totalLocations) * 100, 2)
                : 0,
        ]);
    }

    /**
     * Consulta base de ubicaciones del inventario con su estatus de comparación
     * (físico vs WMS), compartida por el datatable y los KPIs.
     */
    private function baseLocationCountsQuery(Request $request, InventoryHead $inventoryHead)
    {
        return DB::query()
            ->fromSub(function ($query) use ($inventoryHead, $request) {
                $query->from('inventory_location_counts as ilc')
                    ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
                    ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
                    ->leftJoin('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
                    ->leftJoin('inventorypartnumbers as iv1', 'ilc.inventorypartnumber01_id', '=', 'iv1.id')
                    ->leftJoin('inventorypartnumbers as iv2', 'ilc.inventorypartnumber02_id', '=', 'iv2.id')
                    ->leftJoin('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
                    ->leftJoin('inventoryunitsmeasure as um', 'il.inventoryunitsmeasure_id', '=', 'um.id')
                    ->join('inventory_stock_wms as wm', function ($join) {
                        $join->on('ilc.inventory_location_id', '=', 'wm.inventory_location_id')
                            ->on('wm.inventory_head_id', '=', 'ilc.inventory_head_id');
                    }, null, null, 'full outer')
                    ->where('ilc.inventory_head_id', $inventoryHead->id)
                    ->when($request->filled('aislename'), function ($q) use ($request) {
                        $q->where('ia.name', 'like', '%' . $request->aislename . '%');
                    })
                    ->when($request->filled('cardname'), function ($q) use ($request) {
                        $q->where('ic.name', 'like', '%' . $request->cardname . '%');
                    })
                    ->when($request->filled('locationname'), function ($q) use ($request) {
                        $q->where('il.name', 'like', '%' . $request->locationname . '%');
                    })
                    ->when($request->filled('partnumberid'), function ($q) use ($request) {
                        $q->where('iv1.prtnum', 'like', '%' . $request->partnumberid . '%');
                    })
                    ->select([
                        'ilc.inventory_head_id',
                        'ia.name as aisle_name',
                        'ic.name as card_name',
                        'il.name as location_name',
                        'iv1.prtnum as part_number1',
                        'iv1.lngdsc as description01',
                        'ilc.quantity01',
                        'um.unitmeasureid',
                        'iv1.untpal as cspal',
                        'iv1.untcas as pzcs',
                        DB::raw("IIF(um.unitmeasureid = 'PA', ilc.quantity01 * iv1.untpal * iv1.untcas, ilc.quantity01 * iv1.untcas) as total_piezas_fisico"),
                        'wm.quantity_location as total_piezas_wms',
                    ]);
            }, 'base')
            ->select('*')
            ->selectRaw("
				CASE
					WHEN part_number1 IS NULL OR LTRIM(RTRIM(part_number1)) = '' THEN 'PENDIENTE'
					WHEN total_piezas_fisico = total_piezas_wms THEN 'OK'
					WHEN total_piezas_fisico IS NULL AND total_piezas_wms IS NULL THEN 'OK'
					ELSE 'ERROR'
				END as status_comparacion
			");
    }
}
