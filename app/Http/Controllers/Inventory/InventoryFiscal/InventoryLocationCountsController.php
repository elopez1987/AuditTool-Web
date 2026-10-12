<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Exports\Inventory\InventoryFiscal\ExportLocationCountsWms;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\UpdateInventoryLocationCountRequest;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class InventoryLocationCountsController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $statusComparacionOptions = $this->statusComparacionOptions();

        return view(
            'Inventory.InventoryHead.InventoryLocationCounts.index',
            compact('inventoryHead', 'statusComparacionOptions'),
        );
    }

    public function getLocationCountsInventory(Request $request, $id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();

        $data = $this->baseLocationCountsQuery($request, $inventoryHead);

        return DataTables::of($data)
            ->orderColumn('aisle_name', 'aisle_name $1')
            ->orderColumn('card_name', 'card_name $1')
            ->orderColumn('location_name', 'location_name $1')
            ->orderColumn('part_number1', 'part_number1 $1')
            ->orderColumn('description01', 'description01 $1')
            ->orderColumn('total_piezas_fisico', 'total_piezas_fisico $1')
            ->orderColumn('part_number_wms', 'part_number_wms $1')
            ->orderColumn('total_piezas_wms', 'total_piezas_wms $1')
            ->orderColumn('unitmeasure', 'unitmeasure $1')
            ->orderColumn('status_sku', 'status_sku $1')
            ->orderColumn('status_cantidad', 'status_cantidad $1')
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
     * Exporta todos los registros de la tabla (respeta filtros activos).
     * Usa chunks de 1000 filas + CSV para soportar +30k sin timeout/memoria.
     */
    public function exportLocationCountsWms(Request $request, $id): BinaryFileResponse
    {
        // Evita corte por max_execution_time en exportaciones grandes.
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();

        $query = $this->baseLocationCountsQuery($request, $inventoryHead);

        $fileName = 'validacion-ubicaciones-wms-' . $inventoryHead->id . '-' . now()->format('Ymd-His') . '.csv';

        return Excel::download(
            new ExportLocationCountsWms($query),
            $fileName,
            ExcelFormat::CSV,
        )->deleteFileAfterSend();
    }

    /**
     * Opciones fijas de estatus de comparación utilizadas por el filtro de la vista.
     *
     * @return array<int, array{value: string, label: string}>
     */
    private function statusComparacionOptions(): array
    {
        return [
            ['value' => 'OK', 'label' => 'OK'],
            ['value' => 'ERROR', 'label' => 'ERROR'],
            ['value' => 'PENDIENTE', 'label' => 'PENDIENTE'],
        ];
    }

    /**
     * Consulta base de ubicaciones del inventario con su estatus de comparación
     * (físico vs WMS), compartida por el datatable y los KPIs.
     */
    private function baseLocationCountsQuery(Request $request, InventoryHead $inventoryHead)
    {
        $resultado = DB::query()
            ->fromSub(function ($query) use ($inventoryHead, $request) {
                $query->from('inventory_location_counts as ilc')
                    ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
                    ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
                    ->leftJoin('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
                    ->leftJoin('inventorypartnumbers as iv1', 'ilc.inventorypartnumber01_id', '=', 'iv1.id')
                    ->leftJoin('inventorypartnumbers as iv2', 'ilc.inventorypartnumber02_id', '=', 'iv2.id')
                    ->leftJoin('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
                    ->leftJoin('inventoryunitsmeasure as um', 'il.inventoryunitsmeasure_id', '=', 'um.id')
                    ->leftJoin('inventory_stock_wms as wm', function ($join) {
                        $join->on('ilc.inventory_location_id', '=', 'wm.inventory_location_id')
                            ->on('wm.inventory_head_id', '=', 'ilc.inventory_head_id');
                    })
                    ->leftJoin('inventorypartnumbers as iv3', 'wm.inventorypartnumber_id', '=', 'iv3.id')
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
                        'ilc.id',
                        'ilc.inventory_head_id',
                        'ia.name as aisle_name',
                        'ic.name as card_name',
                        'il.name as location_name',
                        'iv1.prtnum as part_number1',
                        'iv1.lngdsc as description01',
                        'ilc.quantity01',
                        'um.unitmeasureid',
                        'um.description as unitmeasure',
                        'iv1.untpal as cspal',
                        'iv1.untcas as pzcs',
                        // prtnum del material WMS vía inventorypartnumbers
                        'iv3.prtnum as part_number_wms',
                        // quantity/untpal/untcas son INT: castear a BIGINT evita overflow.
                        // quantity01 NULL debe seguir NULL (no 0) para no marcar PENDIENTE como OK.
                        DB::raw("IIF(
                            um.unitmeasureid = 'PA',
                            cast(ilc.quantity01 as bigint)
                                * cast(isnull(iv1.untpal, 1) as bigint)
                                * cast(isnull(iv1.untcas, 1) as bigint),
                            cast(ilc.quantity01 as bigint)
                                * cast(isnull(iv1.untcas, 1) as bigint)
                        ) as total_piezas_fisico"),
                        DB::raw('cast(wm.quantity_location as bigint) as total_piezas_wms'),
                    ]);
            }, 'base')
            ->select('*')
            ->selectRaw("
                CASE
                    WHEN part_number1 IS NULL OR LTRIM(RTRIM(part_number1)) = '' THEN 'PENDIENTE'
                    WHEN part_number_wms IS NULL OR LTRIM(RTRIM(part_number_wms)) = '' THEN 'PENDIENTE'
                    WHEN part_number1 = part_number_wms THEN 'OK'
                    ELSE 'ERROR'
                END as status_sku,
                CASE
                    WHEN total_piezas_fisico IS NULL AND total_piezas_wms IS NULL THEN 'PENDIENTE'
                    WHEN total_piezas_fisico IS NULL THEN 'PENDIENTE'
                    WHEN isnull(total_piezas_fisico, cast(0 as bigint)) = isnull(total_piezas_wms, cast(0 as bigint)) THEN 'OK'
                    ELSE 'ERROR'
                END as status_cantidad,
                CASE
                    WHEN total_piezas_fisico = 0 AND COALESCE(total_piezas_wms, 0) = 0 THEN 'OK'
                    WHEN part_number1 IS NULL OR LTRIM(RTRIM(part_number1)) = '' THEN 'PENDIENTE'
                    WHEN total_piezas_fisico = total_piezas_wms
                         AND isnull(part_number1, '') = isnull(part_number_wms, '') THEN 'OK'
                    WHEN total_piezas_fisico IS NULL AND total_piezas_wms IS NULL THEN 'OK'
                    ELSE 'ERROR'
                END as status_comparacion
            ");

        return DB::query()
            ->fromSub($resultado, 'resultado')
            ->select('*')
            ->when($request->filled('statuscomparacion'), function ($q) use ($request) {
                $q->where('status_comparacion', $request->statuscomparacion);
            });
    }

    public function updatelocation(UpdateInventoryLocationCountRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $location = InventoryLocationCount::query()
                    ->where('id', $id)
                    ->first();

                if (is_null($location)) {
                    return $this->errorResponse('La ubicación no existe.');
                }

                $partNumber = InventoryPartnumber::controlAccessUser()
                    ->where('prtnum', $request->partnumber01)
                    ->select(['id', 'prtnum'])
                    ->first();

                if (is_null($partNumber)) {
                    return $this->errorResponse('El número de parte no existe: ' . $request->partnumber01);
                }

                $location->update([
                    'inventorypartnumber01_id' => $partNumber->id,
                    'quantity01' => $request->quantity01,
                ]);

                return $this->redirectBackSuccessMessage('Registro actualizado con éxito.');
            });
        } catch (\Throwable $e) {
            Log::error('Error en updatelocation', [
                'location_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ocurrió un error al actualizar la ubicación.');
        }
    }
}
