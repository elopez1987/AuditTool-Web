<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryHeadRequest;
use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use App\Models\Inventory\InventoryFiscal\InventoryAisleCount;
use App\Models\Inventory\InventoryFiscal\InventoryCard;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
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
        $filters = $request->only(['name']);

        $data = InventoryHead::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,name,description',
                'created_by:name,id',
                'closed_by:name,id',
                'warehouse:warehouseid,name'])
            ->Filter($filters)
            ->orderBy('id', 'desc')
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

    public function show($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01'])
            ->findOrFail($id);

        return view('Inventory.InventoryHead.show', compact('inventoryhead'));

    }

    public function edit(InventoryHead $inventoryhead)
    {
        $aislesPlanning = InventoryAisleCount::where('inventory_head_id', $inventoryhead->id)
            ->pluck('inventory_aisle_id');

        $cardsPlanning = InventoryCardCount::where('inventory_head_id', $inventoryhead->id)
            ->pluck('inventory_card_id');

        $aisles = InventoryAisle::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['id', 'name'])
            ->whereNotIn('id', $aislesPlanning)
            ->get();

        $cards = InventoryCard::controlAccessUser()
            ->where('statusid', 'E')
            ->whereIn('inventory_aisle_id', $aislesPlanning)
            ->whereNotIn('id', $cardsPlanning)
            ->select(['id', 'name'])
            ->get();

        return view('Inventory.InventoryHead.edit', compact('inventoryhead', 'aisles', 'cards'));
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
