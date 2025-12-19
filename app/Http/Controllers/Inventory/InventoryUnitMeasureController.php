<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryUnitMeasureController extends BaseController
{
    public function index()
    {
        $this->authorize('inventoryunitmeasure.index');

        return view('Inventory.InventoryUnits.index');
    }

    public function getInventoryUnits(Request $request)
    {
        $data = InventoryUnitMeasure::controlAccessUser()
            ->with(['status:statusid,name', 'center:centerid,name,description', 'warehouse:warehouseid,name'])
            ->Name($request->get('name'))->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Inventory.InventoryUnits.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('inventoryunitmeasure.index');

        return view('Inventory.InventoryUnits.create');
    }

    public function store(Request $request)
    {
        $access = $this->getAccessForUser();
        $category01 = new InventoryUnitMeasure(
            $request->all() +
            ['centerid' => $access->centerid] +
            ['warehouseid' => $access->warehouseid]
        );
        $category01->save();

        return $this->successResponse('inventoryunitmeasure.index');
    }

    public function edit(InventoryUnitMeasure $inventoryunitmeasure)
    {
        $this->authorize('inventoryunitmeasure.edit');
        $status = $this->getStatusSTS();

        return view('Inventory.InventoryUnits.edit', compact('status', 'inventoryunitmeasure'));
    }

    public function update(Request $request, InventoryUnitMeasure $inventoryunitmeasure)
    {
        $inventoryunitmeasure->update($request->all());

        return $this->infoResponse('inventoryunitmeasure.index');
    }

    public function destroy($id) {}
}
