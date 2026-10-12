<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Exports\Inventory\InventoryFiscal\ExportInventoryStockWms;
use App\Http\Controllers\BaseController;
use App\Imports\Inventario\InventoryFiscal\ImportInventoryStockWms;
use App\Models\Inventory\InventoryFiscal\ImportInventoryWms;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportInventoryWmsController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        return view('Inventory.InventoryHead.ImportInventoryWms.index',
            compact('inventoryHead'));
    }

    public function getInventoryStockWms(Request $request, $id)
    {
        $data = $this->baseStockWmsQuery($request, (int) $id);

        return $this->showDataToView($data, '');
    }

    public function getInventoryStockWmsKpis(Request $request, $id)
    {
        $totals = ImportInventoryWms::query()
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
                COUNT(DISTINCT inventory_location_id) as total_locations,
                COUNT(DISTINCT inventorypartnumber_id) as total_part_numbers,
                SUM(quantity_location) as total_quantity_location,
                SUM(quantity_lpns) as total_quantity_lpns
            ')
            ->first();

        return response()->json([
            'totalLocations' => $totals->total_locations ?? 0,
            'totalPartNumbers' => $totals->total_part_numbers ?? 0,
            'totalQuantityLocation' => $totals->total_quantity_location ?? 0,
            'totalQuantityLpns' => $totals->total_quantity_lpns ?? 0,
        ]);
    }

    public function importStockWms(Request $request)
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
            \Excel::import(new ImportInventoryStockWms($inventoryHead), $request->file('file'));
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

    public function exportStockWms(Request $request, $id): BinaryFileResponse
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->firstOrFail();

        $query = $this->baseStockWmsQuery($request, (int) $inventoryHead->id);
        $fileName = 'existencia-wms-' . $inventoryHead->id . '-' . now()->format('Ymd-His') . '.csv';

        return Excel::download(
            new ExportInventoryStockWms($query),
            $fileName,
            ExcelFormat::CSV,
        )->deleteFileAfterSend();
    }

    public function clearDataWms($id)
    {
        Gate::authorize('clear-stock-wms');

        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('id', $id)
            ->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        try {
            ImportInventoryWms::where('inventory_head_id', $inventoryHead->id)->delete();
        } catch (\Throwable $e) {
            Log::error('Error al limpiar existencia WMS', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudieron eliminar los registros importados.');
        }

        return $this->redirectBackSuccessMessage('Existencia WMS eliminada correctamente.');
    }

    private function baseStockWmsQuery(Request $request, int $headId): Builder
    {
        return ImportInventoryWms::query()
            ->with([
                'inventoryhead:id,name',
                'inventorylocation:id,name',
                'inventorypartnumber:id,prtnum,lngdsc',
            ])
            ->select('inventory_stock_wms.*')
            ->where('inventory_stock_wms.inventory_head_id', $headId)
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
