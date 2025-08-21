<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocations;
use App\Models\Inventory\FiscalInventory\Stock\InventoryStock;
use App\Traits\Web\Counting\QueryInventoryHead;
use Illuminate\Http\Request;

class InventoryHeadController extends BaseController
{

    use QueryInventoryHead;

    public function index()
    {
        $this->authorize('inventoryhead.index');
        $status = $this->getStatusForSchema('IBX');
        return view('Counting.InventoryHead.index', compact('status'));
    }

    public function getDataInventory(Request $request)
    {
        $data = $this->getInventoriesFiscal($request);
        return $this->showDataToView($data,
            'Counting.InventoryHead.partials.buttons');
    }

    public function create()
    {
        $this->authorize('inventoryhead.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $types = $this->getStatusForSchema('INC');
        return view('Counting.InventoryHead.create',
            compact('centers', 'warehouses', 'types'));
    }

    public function store(Request $request)
    {
        $inventory = new  InventoryHead($request->all());
        $inventory->save();
        return redirect('inventoryhead/' . $inventory->id . '/edit');
    }

    public function edit(InventoryHead $inventoryhead)
    {
        $this->authorize('inventoryhead.edit');
        $totalCards = InventoryCard::where('inventoryid', $inventoryhead->id)->count();
        $totalSkus = InventoryStock::where('inventoryid', $inventoryhead->id)
            ->distinct()->count('partnumberid');
        if ($inventoryhead->inventorytype == 'APP') {
            $totalLocations = InventoryLocations::where('inventoryid', $inventoryhead->id)->count();
        } else {
            $totalLocations = InventoryLocationForDron::where('inventoryid', $inventoryhead->id)->count();
        }
        return view('Counting.InventoryHead.edit', compact('inventoryhead', 'totalLocations', 'totalCards', 'totalSkus'));
    }

    public function update(InventoryHead $inventoryhead, Request $request)
    {
        $inventoryhead->update($request->all());
        $inventoryhead->save();
        return $this->redirectBackSuccessMessage('Modificación realizada con exito');
    }

    public function show(InventoryHead $inventoryhead)
    {
        $this->authorize('inventoryhead.show');
        $totales = $this->getTotales($inventoryhead);
        $locations = $this->getLocations($inventoryhead);
        $contados = $this->getContados($inventoryhead);
        $totalContados = $this->getTotalCount($inventoryhead);
        $totalErrors = $this->getTotalErrorsInventory($inventoryhead);
        return view('Counting.InventoryHead.show',
            compact('inventoryhead', 'totales', 'locations',
                'contados', 'totalContados', 'totalErrors'));
    }

    public function destroy($id)
    {
        $this->authorize('inventoryhead.destroy');
        InventoryHead::destroy($id);
        return $this->redirectBackSuccessMessage('Inventario eliminado con exito!!');
    }


}
