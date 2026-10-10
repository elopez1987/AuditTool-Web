<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Database\Query\Builder;
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
        $data = $this->buildValidationQuery((int) $id)
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

    public function getDataValidationSapKpis($id)
    {
        $totals = DB::query()
            ->fromSub($this->buildValidationQuery((int) $id), 'v')
            ->selectRaw('
                isnull(sum(cast(v.total_piezas_conteo_mas_transitorias as bigint)), cast(0 as bigint)) as total_fisico,
                isnull(sum(cast(v.total_piezas_sap as bigint)), cast(0 as bigint)) as total_sap,
                isnull(sum(cast(v.stock_wms as bigint)), cast(0 as bigint)) as total_wms,
                sum(case when v.status_conteo_vs_sap = \'ERROR\' then 1 else 0 end) as errors_fisico_vs_sap,
                sum(case when v.status_sap_vs_wms = \'ERROR\' then 1 else 0 end) as errors_sap_vs_wms
            ')
            ->first();

        return response()->json([
            'totalFisico' => $totals->total_fisico ?? 0,
            'totalSap' => $totals->total_sap ?? 0,
            'totalWms' => $totals->total_wms ?? 0,
            'errorsFisicoVsSap' => $totals->errors_fisico_vs_sap ?? 0,
            'errorsSapVsWms' => $totals->errors_sap_vs_wms ?? 0,
        ]);
    }

    /**
     * Conciliación por número de parte: físico (+ transitorias) vs SAP vs WMS.
     */
    private function buildValidationQuery(int $headId): Builder
    {
        // quantity/untpal/untcas son INT: castear a BIGINT evita overflow en multiplicaciones
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
                sum(
                    iif(
                        um.unitmeasureid = 'PA',
                        cast(ilc.quantity01 as bigint)
                            * cast(isnull(iv1.untpal, 1) as bigint)
                            * cast(isnull(iv1.untcas, 1) as bigint),
                        cast(ilc.quantity01 as bigint)
                            * cast(isnull(iv1.untcas, 1) as bigint)
                    )
                ) as total_piezas_conteo
            ");

        $transitorios = DB::table('inventory_transitionals as it')
            ->join('inventorypartnumbers as iv1', 'it.inventorypartnumber_id', '=', 'iv1.id')
            ->where('it.inventory_head_id', $headId)
            ->groupBy('it.inventorypartnumber_id')
            ->selectRaw('
                it.inventorypartnumber_id,
                sum(
                    cast(it.quantity as bigint)
                        * cast(isnull(iv1.untcas, 1) as bigint)
                ) as total_piezas_transitorias
            ');

        $wms = DB::table('inventory_stock_wms as isw')
            ->where('isw.inventory_head_id', $headId)
            ->groupBy('isw.inventorypartnumber_id')
            ->selectRaw('
                isw.inventorypartnumber_id,
                sum(cast(isw.quantity_location as bigint)) as stock_wms
            ');

        $sap = DB::table('inventory_stock_sap')
            ->where('inventory_head_id', $headId)
            ->selectRaw('
                inventorypartnumber_id,
                cast(quantity as bigint) as total_piezas_sap
            ');

        return DB::query()
            ->fromSub($fisico, 'f')
            ->leftJoinSub($transitorios, 't', 't.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->leftJoinSub($wms, 'w', 'w.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->leftJoinSub($sap, 's', 's.inventorypartnumber_id', '=', 'f.partnumber_id')
            ->selectRaw("
                f.idh_01 as partnumber,
                f.description,
                f.total_piezas_conteo,
                isnull(t.total_piezas_transitorias, cast(0 as bigint)) as total_piezas_transitorias,
                f.total_piezas_conteo + isnull(t.total_piezas_transitorias, cast(0 as bigint)) as total_piezas_conteo_mas_transitorias,
                isnull(w.stock_wms, cast(0 as bigint)) as stock_wms,
                isnull(s.total_piezas_sap, cast(0 as bigint)) as total_piezas_sap,
                case
                    when f.total_piezas_conteo + isnull(t.total_piezas_transitorias, cast(0 as bigint))
                         = isnull(s.total_piezas_sap, cast(0 as bigint))
                        then 'CORRECTO' else 'ERROR'
                end as status_conteo_vs_sap,
                case
                    when isnull(s.total_piezas_sap, cast(0 as bigint))
                         = isnull(w.stock_wms, cast(0 as bigint))
                        then 'CORRECTO' else 'ERROR'
                end as status_sap_vs_wms
            ");
    }
}
