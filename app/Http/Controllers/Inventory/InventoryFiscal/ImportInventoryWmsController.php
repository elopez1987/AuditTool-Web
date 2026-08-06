<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\PartNumbers\ImportInventoryStockWms;
use App\Models\Inventory\InventoryFiscal\ImportInventoryWms;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
