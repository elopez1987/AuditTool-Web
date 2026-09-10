<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\PartNumbers\ImportInventoryTransitional;
use App\Models\Inventory\InventoryFiscal\ImportInventoryWms;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getInventoryStockWms($id)
    {
        $data = ImportInventoryWms::with([
            'inventoryhead:id,name',
            'inventorylocation:id,name',
            'inventorypartnumber:id,prtnum,lngdsc',
        ])
            ->select('inventory_stock_wms.*')
            ->where('inventory_head_id', $id);

        return $this->showDataToView($data, '');
    }

    /**
     * Cards de la parte superior de la vista: total de ubicaciones, total de
     * números de parte y las sumas totales de quantity_location y
     * quantity_lpns para este inventario.
     */
    public function getInventoryStockWmsKpis($id)
    {
        $totals = ImportInventoryWms::where('inventory_head_id', $id)
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
}
