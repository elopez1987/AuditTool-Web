<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryAisleRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use Illuminate\Http\Request;

class InventoryAisleController extends BaseController
{
    public function index()
    {
        $status = SystemStatus::whereSchemaid('STS')
            ->select(['statusid', 'name', 'comment'])
            ->whereIN('statusid', ['D', 'E'])
            ->get();

        return view('Inventory.InventoryAisle.index',
            compact('status'));
    }

    public function getInventoryAisle(Request $request)
    {
        $data = InventoryAisle::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,name,description',
                'warehouse:warehouseid,name'])
            ->get();

        return $this->showDataToView($data,
            'Inventory.InventoryAisle.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Inventory.InventoryAisle.create',
            compact('centers', 'warehouses'));
    }

    public function store(InventoryAisleRequest $request)
    {
        InventoryAisle::create($request->validated());

        return $this->successResponse('inventoryaisles.index');
    }

    public function edit(InventoryAisle $inventoryaisle)
    {
        $status = SystemStatus::whereSchemaid('STS')
            ->select(['statusid', 'name', 'comment'])
            ->whereIN('statusid', ['D', 'E'])
            ->get();

        return view('Inventory.InventoryAisle.edit',
            compact('inventoryaisle', 'status'));
    }

    public function update(Request $request, InventoryAisle $inventoryaisle)
    {
        $inventoryaisle->update($request->all());

        return $this->successResponse('inventoryaisles.index');
    }
}
