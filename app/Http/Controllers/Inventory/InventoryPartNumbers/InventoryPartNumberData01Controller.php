<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\PartNumber\InventoryPartNumberData01;
use Illuminate\Http\Request;

class InventoryPartNumberData01Controller extends BaseController
{
    public function index($id)
    {

        $inventoryPartNumber = InventoryPartNumber::findOrFail($id);
        $inventoryPartNumberData01 = InventoryPartNumberData01::where('inventorypartnumber_id', $id)->first();

        return view('Inventory.InventoryPartnumbers.Data01.index',
            compact('inventoryPartNumber', 'inventoryPartNumberData01'));

    }

    public function store(Request $request, $id)
    {

        $inventoryPartNumber = InventoryPartNumber::findOrFail($id);
        InventoryPartNumberData01::updateOrCreate([
            'inventorypartnumber_id' => $inventoryPartNumber->id,
        ], [
            'container_type' => $request->input('container_type'),
            'pn_turbo' => $request->input('pn_turbo'),
            'quantity' => $request->input('quantity'),
            'machines_component' => $request->input('machines_component'),
        ]);

        return $this->redirectBackSuccessMessage('Datos guardados con exito!!');
    }
}
