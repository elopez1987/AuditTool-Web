<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Imports\Inventario\Fiscal\ImportLocationDron;
use App\Imports\Inventario\Fiscal\ImportLocationForApp;
use App\Imports\Inventario\Fiscal\ImportStockInventory;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Stock\InventoryStock;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportInventoryLocationController extends BaseController
{
    public function importinventorylocation(Request $request)
    {
        ini_set('max_execution_time', 400);
        $inventoryHead = InventoryHead::where('id', $request['inventoryid'])->first();
        $file = $request->file('file');
        if ($inventoryHead->inventorytype == 'APP') {
            Excel::import(new ImportLocationForApp(), $file);
        }
        if ($inventoryHead->inventorytype == 'DRON') {
            Excel::import(new ImportLocationDron(), $file);
        }
        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }

    public function importinventorystock(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportStockInventory($request['inventoryid']), $file);
        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }

    public function addstockforinventory(Request $request)
    {
        $inventoryHead = InventoryHead::where('id', $request['inventoryid'])->first();
        InventoryStock::create([
            'centerid' => $inventoryHead->centerid,
            'warehouseid' => $inventoryHead->warehouseid,
            'inventoryid' => $inventoryHead->id,
            'partnumberid' => $request['partnumberid'],
            'locationid' => $request['locationid'],
            'quantity' => $request['totalbox'],
            'lpnid' => $request['totallpns'],
        ]);
        return response()->json('Registro guardado con exito');
    }


}
