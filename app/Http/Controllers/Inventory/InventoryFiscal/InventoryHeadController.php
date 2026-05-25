<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryHeadRequest;
use App\Models\Inventory\InventoryHead;
use App\Traits\Web\Inventory\InventoryFiscalTrait;
use Illuminate\Http\Request;

class InventoryHeadController extends BaseController
{
    use InventoryFiscalTrait;

    public function index()
    {

        $status = $this->getStatusForSchema('INF');

        return view('Inventory.InventoryHead.index', compact('status'));
    }

    public function getInventoryHead(Request $request)
    {
        $data = InventoryHead::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01', 'center:centerid,name,description', 'warehouse:warehouseid,name'])
//            ->Name($request->get('name'))
            ->get();

        return $this->showDataToView($data, 'Inventory.InventoryHead.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Inventory.InventoryHead.create',
            compact('centers', 'warehouses'));

    }

    public function store(InventoryHeadRequest $request)
    {
        InventoryHead::create($request->validated());

        return $this->successResponse('inventoryheads.index');
    }

    public function show($id) {}

    public function edit(InventoryHead $inventoryhead)
    {

        return view('Inventory.InventoryHead.edit', compact('inventoryhead'));
    }

    public function update(Request $request, InventoryHead $inventoryhead)
    {
        $inventoryhead->update($request->all());

        return $this->successResponse('inventoryheads.index');
    }

    public function destroy(InventoryHead $inventoryhead)
    {
        ($inventoryhead->delete()) ? $this->successResponse('inventoryheads.index') : $this->errorResponse('inventoryheads.index');
    }
}
