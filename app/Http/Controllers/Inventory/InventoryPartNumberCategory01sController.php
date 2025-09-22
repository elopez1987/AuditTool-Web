<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\CategoryInventory\CreateCategoryInventory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use Illuminate\Http\Request;

class InventoryPartNumberCategory01sController extends BaseController
{
    public function index()
    {
        $this->authorize('inventorypartnumbercategory01.index');
        return view('Inventory.InventoryPartNumberCategory01.index');
    }

    public function getDataCategory01(Request $request)
    {
        $data = InventoryPartNumberCategory01::controlAccessUser()
            ->with(['status', 'center', 'warehouse'])
            ->Name($request->get('name'))->get();
        return $this->showDataToView(
            $data,
            'Inventory.InventoryPartNumberCategory01.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('inventorypartnumbercategory01.create');
        return view('Inventory.InventoryPartNumberCategory01.create');
    }

    public function store(CreateCategoryInventory01 $request)
    {
        $access = $this->getAccessForUser();
        $category01 = new InventoryPartNumberCategory01(
            $request->all() +
            ['centerid' => $access->centerid] +
            ['warehouseid' => $access->warehouseid]
        );
        $category01->save();
        return $this->successResponse('inventorypartnumbercategory01.index');
    }

    public function edit(InventoryPartNumberCategory01 $inventorypartnumbercategory01)
    {
        $this->authorize('inventorypartnumbercategory01.edit');
        $status = $this->getStatusForSchema('STS');
        return view(
            'Inventory.InventoryPartNumberCategory01.edit',
            compact('status', 'inventorypartnumbercategory01')
        );
    }

    public function update(Request $request, InventoryPartNumberCategory01 $inventorypartnumbercategory01)
    {
        $inventorypartnumbercategory01->update($request->all());
        return $this->infoResponse('inventorypartnumbercategory01.index');
    }
}
