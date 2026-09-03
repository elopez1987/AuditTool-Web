<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryCardRequest;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
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
        $data = InventoryCard::with(['status:statusid,name,auxiliary01',
            'inventoryarea:id,name',
            'inventoryaisle:id,name',
        ])
            ->select(['inventory_cards.id', 'inventory_cards.name', 'inventory_area_id', 'inventory_aisle_id', 'inventory_cards.statusid',
            ])
            ->when($request->name, function ($query) use ($request) {
                $query->where('inventory_cards.name', $request->name);
            })
            ->when($request->inventory_area_id, function ($query) use ($request) {
                $query->where('inventory_cards.inventory_area_id', $request->inventory_area_id);
            })
            ->when($request->inventory_aisle_id, function ($query) use ($request) {
                $query->where('inventory_cards.inventory_aisle_id', $request->inventory_aisle_id);
            });

        return $this->showDataToView($data,
            'Inventory.InventoryCard.partials.buttons');
    }

    public function create()
    {
        $areas = $this->getAreas();
        $aisles = $this->getAisles();

        return view('Inventory.InventoryCard.create',
            compact('areas', 'aisles'));
    }

    public function store(InventoryCardRequest $request)
    {
        InventoryCard::create($request->validated());

        return $this->successResponse('inventorycards.index');
    }

    public function show(InventoryCard $inventorycard)
    {
        $unitmeasures = InventoryUnitMeasure::where('statusid', 'E')
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
