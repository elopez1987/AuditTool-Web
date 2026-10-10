<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryValidationSapController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        return view(
            'Inventory.InventoryHead.ValidationStock.index',
            compact('inventoryhead')
        );
    }

    public function getDataValidationSap(Request $request, $id)
    {
        $headId = (int) $id;

        // CTE fisico
        $fisico = DB::table('inventory_location_counts as ilc')
            ->join('inventorypartnumbers as iv1', 'ilc.inventorypartnumber01_id', '=', 'iv1.id')
            ->leftJoin('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->leftJoin('inventoryunitsmeasure as um', 'il.inventoryunitsmeasure_id', '=', 'um.id')
            ->where('ilc.inventory_head_id', $headId)
            ->groupBy('ilc.inventorypartnumber01_id', 'iv1.prtnum', 'iv1.lngdsc')
            ->selectRaw("
                ilc.inventorypartnumber01_id as partnumber_id,
                iv1.prtnum as idh_01,
                iv1.lngdsc as description,
                sum(iif(um.unitmeasureid = 'PA',
                        ilc.quantity01 * iv1.untpal * iv1.untcas,
                        ilc.quantity01 * iv1.untcas)) as total_piezas_conteo
            ");

        // CTE transitorios
        $transitorios = DB::table('inventory_transitionals as it')
            ->join('inventorypartnumbers as iv1', 'it.inventorypartnumber_id', '=', 'iv1.id')
            ->where('it.inventory_head_id', $headId)
            ->groupBy('it.inventorypartnumber_id')
            ->selectRaw('
                it.inventorypartnumber_id,
                sum(it.quantity * iv1.untcas) as total_piezas_transitorias
            ');

        // CTE wms
        $wms = DB::table('inventory_stock_wms as isw')
            ->where('isw.inventory_head_id', $headId)
            ->groupBy('isw.inventorypartnumber_id')
            ->selectRaw('
                isw.inventorypartnumber_id,
                sum(isw.quantity_location) as stock_wms
            ');

        // CTE sap
        $sap = DB::table('inventory_stock_sap')
            ->where('inventory_head_id', $headId)
            ->select('inventorypartnumber_id', 'quantity as total_piezas_sap');

        // Consulta final (query builder para server-side DataTables)
        $data = DB::query()
            ->fromSub($fisico, 'f')
            ->leftJoinSub($transitorios, 't', 't.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->leftJoinSub($wms, 'w', 'w.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->leftJoinSub($sap, 's', 's.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->selectRaw("
                f.idh_01 as partnumber,
                f.description,
                f.total_piezas_conteo,
                isnull(t.total_piezas_transitorias, 0) as total_piezas_transitorias,
                f.total_piezas_conteo + isnull(t.total_piezas_transitorias, 0) as total_piezas_conteo_mas_transitorias,
                isnull(w.stock_wms, 0) as stock_wms,
                isnull(s.total_piezas_sap, 0) as total_piezas_sap,
                case
                    when f.total_piezas_conteo + isnull(t.total_piezas_transitorias, 0) = isnull(s.total_piezas_sap, 0)
                        then 'CORRECTO' else 'ERROR'
                end as status_conteo_vs_sap,
                case
                    when isnull(s.total_piezas_sap, 0) = isnull(w.stock_wms, 0)
                        then 'CORRECTO' else 'ERROR'
                end as status_sap_vs_wms
            ")
            ->when($request->filled('partnumber'), function ($query) use ($request) {
                $term = trim((string) $request->input('partnumber'));

                $query->where(function ($q) use ($term) {
                    $q->where('f.idh_01', 'like', '%' . $term . '%')
                        ->orWhere('f.description', 'like', '%' . $term . '%');
                });
            });

        // No usar orderBy aquí: Yajra envuelve la query en un subquery para el COUNT
        // y SQL Server rechaza ORDER BY sin TOP/OFFSET en derived tables.
        return DataTables::of($data)
            ->orderColumn('partnumber', 'f.idh_01 $1')
            ->orderColumn('description', 'f.description $1')
            ->orderColumn('total_piezas_conteo', 'f.total_piezas_conteo $1')
            ->orderColumn('total_piezas_transitorias', 'isnull(t.total_piezas_transitorias, 0) $1')
            ->orderColumn('total_piezas_conteo_mas_transitorias', 'f.total_piezas_conteo + isnull(t.total_piezas_transitorias, 0) $1')
            ->orderColumn('stock_wms', 'isnull(w.stock_wms, 0) $1')
            ->orderColumn('total_piezas_sap', 'isnull(s.total_piezas_sap, 0) $1')
            ->orderColumn('status_conteo_vs_sap', 'status_conteo_vs_sap $1')
            ->orderColumn('status_sap_vs_wms', 'status_sap_vs_wms $1')
            ->toJson();
    }
}
