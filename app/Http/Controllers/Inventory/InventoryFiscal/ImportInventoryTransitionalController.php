<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Exports\Inventory\InventoryFiscal\ExportInventoryStockTransitional;
use App\Http\Controllers\BaseController;
use App\Imports\Inventario\InventoryFiscal\ImportInventoryTransitional;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryTransitional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportInventoryTransitionalController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        return view('Inventory.InventoryHead.ImportInventoryTransitional.index',
            compact('inventoryHead'));
    }

    public function getInventoryStockTransitional(Request $request, $id)
    {
        $data = $this->baseStockTransitionalQuery($request, (int) $id);

        return $this->showDataToView($data, '');
    }

    public function getInventoryStockTransitionalKpis(Request $request, $id)
    {
        $totals = InventoryTransitional::query()
            ->where('inventory_head_id', (int) $id)
            ->when($request->filled('partnumber'), function ($query) use ($request) {
                $term = trim((string) $request->input('partnumber'));

                $query->whereHas('inventorypartnumber', function ($q) use ($term) {
                    $q->where('prtnum', 'like', '%' . $term . '%')
                        ->orWhere('lngdsc', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('locationname'), function ($query) use ($request) {
                $term = trim((string) $request->input('locationname'));

                $query->whereHas('inventorylocation', function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%');
                });
            })
            ->selectRaw('
                COUNT(DISTINCT inventorypartnumber_id) as total_part_numbers,
                SUM(quantity) as total_quantity
            ')
            ->first();

        return response()->json([
            'totalPartNumbers' => $totals->total_part_numbers ?? 0,
            'totalQuantity' => $totals->total_quantity ?? 0,
        ]);
    }

    public function importStockTransitional(Request $request)
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
            \Excel::import(new ImportInventoryTransitional($inventoryHead), $request->file('file'));
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

    public function exportStockTransitional(Request $request, $id): BinaryFileResponse
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();

        $query = $this->baseStockTransitionalQuery($request, (int) $inventoryHead->id);
        $fileName = 'existencia-transitorias-' . $inventoryHead->id . '-' . now()->format('Ymd-His') . '.csv';

        return Excel::download(
            new ExportInventoryStockTransitional($query),
            $fileName,
            ExcelFormat::CSV,
        )->deleteFileAfterSend();
    }

    public function clearDataTransitional($id)
    {
        Gate::authorize('clear-stock-transitional');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        try {
            InventoryTransitional::where('inventory_head_id', $inventoryHead->id)->delete();
        } catch (\Throwable $e) {
            Log::error('Error al limpiar existencia transitoria', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudieron eliminar los registros importados.');
        }

        return $this->redirectBackSuccessMessage('Existencia transitoria eliminada correctamente.');
    }

    private function baseStockTransitionalQuery(Request $request, int $headId): Builder
    {
        return InventoryTransitional::query()
            ->with([
                'inventoryhead:id,name',
                'inventorylocation:id,name',
                'inventorypartnumber:id,prtnum,lngdsc',
            ])
            ->select('inventory_transitionals.*')
            ->where('inventory_transitionals.inventory_head_id', $headId)
            ->when($request->filled('partnumber'), function ($query) use ($request) {
                $term = trim((string) $request->input('partnumber'));

                $query->whereHas('inventorypartnumber', function ($q) use ($term) {
                    $q->where('prtnum', 'like', '%' . $term . '%')
                        ->orWhere('lngdsc', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('locationname'), function ($query) use ($request) {
                $term = trim((string) $request->input('locationname'));

                $query->whereHas('inventorylocation', function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%');
                });
            });
    }
}
