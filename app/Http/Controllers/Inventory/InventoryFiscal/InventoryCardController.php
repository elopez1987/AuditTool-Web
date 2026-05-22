<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryCardRequest;
use App\Models\Inventory\InventoryCard;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Traits\Web\Inventory\InventoryFiscalTrait;
use Illuminate\Http\Request;

class InventoryCardController extends BaseController
{
    use InventoryFiscalTrait;

    public function index()
    {
        $status = $this->getStatusSTS();
        $areas = $this->getAreas();
        $aisles = $this->getAisles();

        return view('Inventory.InventoryCard.index',
            compact('status', 'areas', 'aisles'));

    }

    public function getInventoryCard(Request $request)
    {
        $data = InventoryCard::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,name,description',
                'warehouse:warehouseid,name',
                'inventoryarea:id,name',
                'inventoryaisle:id,name',
            ])
            ->get();

        return $this->showDataToView($data,
            'Inventory.InventoryCard.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $areas = $this->getAreas();
        $aisles = $this->getAisles();

        return view('Inventory.InventoryCard.create',
            compact('centers', 'warehouses', 'areas', 'aisles'));
    }

    public function store(InventoryCardRequest $request)
    {
        InventoryCard::create($request->validated());

        return $this->successResponse('inventorycards.index');
    }

    public function show(InventoryCard $inventorycard)
    {
        $unitmeasures = InventoryUnitMeasure::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['id', 'description'])
            ->get();
        $status = $this->getStatusSTS();

        return view('Inventory.InventoryCard.show',
            compact('inventorycard', 'unitmeasures', 'status'));
    }

    public function edit(InventoryCard $inventorycard)
    {

        $areas = $this->getAreas();
        $aisles = $this->getAisles();
        $status = $this->getStatusSTS();

        return view('Inventory.InventoryCard.edit',
            compact('inventorycard', 'status', 'areas', 'aisles'));
    }

    public function update(Request $request, InventoryCard $inventorycard)
    {

        $inventorycard->update($request->all());

        return $this->successResponse('inventorycards.index');
    }

    public function destroy(InventoryCard $inventorycard) {}
}
