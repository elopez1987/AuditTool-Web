<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\PartNumbers\ImportInventoryStockWms;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;

class ImportInventoryWmsController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)->first();

        if (is_null($inventoryHead)) {
            return $this->errorResponse('Inventory head not found');
        }

        return view('Inventory.InventoryHead.ImportInventoryWms.index', compact('inventoryHead'));

    }

    public function show($id) {}

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
            Excel::import(new ImportInventoryStockWms($inventoryHead), $request->file('file'));
            //			(new ImportInventoryStockWms($inventoryHead))
            //				->queue($request->file('file'))
            //				->allOnQueue('yms-web');
        } catch (\Throwable $e) {
            Log::error('Error al encolar import de inventario', [
                'inventory_head_id' => $inventoryHead->id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('No se pudo iniciar la importación.');
        }

        return $this->redirectBackSuccessMessage(
            'Tu archivo se está procesando en segundo plano. Te notificaremos cuando termine.'
        );
    }
}
