<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\InventoryFiscal\ImportInventoryStockSap;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryStockSap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getInventoryStockSap($id)
    {
        $data = InventoryStockSap::with([
            'inventoryhead:id,name',
            'inventorypartnumber:id,prtnum,lngdsc',
        ])
            ->select('inventory_stock_sap.*')
            ->where('inventory_head_id', $id);

        return $this->showDataToView($data, '');
    }

    /**
     * Cards de la parte superior de la vista: total de números de parte y
     * suma total de la cantidad importada para este inventario.
     */
    public function getInventoryStockSapKpis($id)
    {
        $totals = InventoryStockSap::where('inventory_head_id', $id)
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
}
