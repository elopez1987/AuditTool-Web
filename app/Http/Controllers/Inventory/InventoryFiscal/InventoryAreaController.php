<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryAreaRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\InventoryFiscal\InventoryArea;
use Illuminate\Http\Request;

class InventoryAreaController extends BaseController
{
    public function index()
    {
        $status = SystemStatus::whereSchemaid('STS')
            ->select(['statusid', 'name', 'comment'])
            ->whereIN('statusid', ['D', 'E'])
            ->get();

        return view('Inventory.InventoryArea.index',
            compact('status'));

    }

    public function getInventoryArea()
    {
        $data = InventoryArea::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,name,description',
                'warehouse:warehouseid,name'])
            ->get();

        return $this->showDataToView($data, 'Inventory.InventoryArea.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Inventory.InventoryArea.create',
            compact('centers', 'warehouses'));
    }

    public function store(InventoryAreaRequest $request)
    {
        InventoryArea::create($request->validated());

        return $this->successResponse('inventoryareas.index');
    }

    public function edit(InventoryArea $inventoryarea)
    {
        $status = SystemStatus::whereSchemaid('STS')
            ->select(['statusid', 'name', 'comment'])
            ->whereIN('statusid', ['D', 'E'])
            ->get();

        return view('Inventory.InventoryArea.edit',
            compact('inventoryarea', 'status'));
    }

    public function update(Request $request, InventoryArea $inventoryarea)
    {
        $inventoryarea->update($request->all());

        return $this->successResponse('inventoryareas.index');
    }
}
