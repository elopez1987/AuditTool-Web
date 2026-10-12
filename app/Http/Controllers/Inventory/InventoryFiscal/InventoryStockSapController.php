<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Exports\Inventory\InventoryFiscal\ExportInventoryStockSap;
use App\Http\Controllers\BaseController;
use App\Imports\Inventario\InventoryFiscal\ImportInventoryStockSap;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryStockSap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InventoryStockSapController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        return view('Inventory.InventoryHead.ImportInventorySap.index',
            compact('inventoryHead'));
    }

    public function getInventoryStockSap(Request $request, $id)
    {
        $data = $this->baseStockSapQuery($request, (int) $id);

        return $this->showDataToView($data, '');
    }

    /**
     * Cards de la parte superior de la vista: total de números de parte y
     * suma total de la cantidad importada para este inventario.
     */
    public function getInventoryStockSapKpis(Request $request, $id)
    {
        $totals = InventoryStockSap::query()
            ->where('inventory_head_id', (int) $id)
            ->when($request->filled('partnumber'), function ($query) use ($request) {
                $term = trim((string) $request->input('partnumber'));

                $query->whereHas('inventorypartnumber', function ($q) use ($term) {
                    $q->where('prtnum', 'like', '%' . $term . '%')
                        ->orWhere('lngdsc', 'like', '%' . $term . '%');
                });
            })
            ->selectRaw('COUNT(*) as total_part_numbers, SUM(quantity) as total_quantity')
            ->first();

        return response()->json([
            'totalPartNumbers' => $totals->total_part_numbers ?? 0,
            'totalQuantity' => $totals->total_quantity ?? 0,
        ]);
    }

    public function importStockSAP(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
            'inventoryid' => ['required', 'integer'],
        ]);

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $validated['inventoryid'])
            ->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        try {
            \Excel::import(new ImportInventoryStockSap($inventoryHead), $request->file('file'));
        } catch (\Throwable $e) {
            Log::error('Error al encolar import de inventario', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudo iniciar la importación.');
        }

        return $this->redirectBackSuccessMessage(
            'Tu archivo se está procesando en segundo plano. Te notificaremos cuando termine.',
        );
    }

    /**
     * Exporta todos los registros importados de SAP (respeta filtro de material).
     */
    public function exportStockSap(Request $request, $id): BinaryFileResponse
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();

        $query = $this->baseStockSapQuery($request, (int) $inventoryHead->id);

        $fileName = 'existencia-sap-' . $inventoryHead->id . '-' . now()->format('Ymd-His') . '.csv';

        return Excel::download(
            new ExportInventoryStockSap($query),
            $fileName,
            ExcelFormat::CSV,
        )->deleteFileAfterSend();
    }

    /**
     * Elimina todos los registros de existencia SAP del inventario.
     */
    public function clearDataSAP($id)
    {
        Gate::authorize('clear-stock-sap');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        try {
            InventoryStockSap::where('inventory_head_id', $inventoryHead->id)->delete();
        } catch (\Throwable $e) {
            Log::error('Error al limpiar existencia SAP', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudieron eliminar los registros importados.');
        }

        return $this->redirectBackSuccessMessage('Existencia SAP eliminada correctamente.');
    }

    /**
     * Consulta base de stock SAP con filtro opcional por número de parte.
     */
    private function baseStockSapQuery(Request $request, int $headId): Builder
    {
        return InventoryStockSap::query()
            ->with([
                'inventoryhead:id,name',
                'inventorypartnumber:id,prtnum,lngdsc',
            ])
            ->select('inventory_stock_sap.*')
            ->where('inventory_stock_sap.inventory_head_id', $headId)
            ->when($request->filled('partnumber'), function ($query) use ($request) {
                $term = trim((string) $request->input('partnumber'));

                $query->whereHas('inventorypartnumber', function ($q) use ($term) {
                    $q->where('prtnum', 'like', '%' . $term . '%')
                        ->orWhere('lngdsc', 'like', '%' . $term . '%');
                });
            });
    }
}
