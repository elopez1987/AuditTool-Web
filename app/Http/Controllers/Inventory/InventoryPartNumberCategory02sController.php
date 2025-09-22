<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\CategoryInventory\CreateCategoryInventory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use Illuminate\Http\Request;

class InventoryPartNumberCategory02sController extends BaseController
{
    public function index()
    {
        $this->authorize('inventorypartnumbercategory02.index');
        return view('Inventory.InventoryPartNumberCategory02.index');
    }

    public function getDataCategory02(Request $request)
    {
        $data = InventoryPartNumberCategory02::controlAccessUser()
            ->with(['status', 'center', 'warehouse'])
            ->Status($request->get('statusid'))
            ->Name($request->get('name'))->get();
        return $this->showDataToView(
            $data,
            'Inventory.InventoryPartNumberCategory02.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('inventorypartnumbercategory02.create');
        return view('Inventory.InventoryPartNumberCategory02.create');
    }

    public function store(CreateCategoryInventory01 $request)
    {
        $access = $this->getAccessForUser();
        $category01 = new InventoryPartNumberCategory02(
            $request->all() +
            ['centerid' => $access->centerid] +
            ['warehouseid' => $access->warehouseid]
        );
        $category01->save();
        return $this->successResponse('inventorypartnumbercategory02.index');
    }

    public function edit(InventoryPartNumberCategory02 $inventorypartnumbercategory02)
    {
        $this->authorize('inventorypartnumbercategory02.edit');
        $status = $this->getStatusForSchema('STS');
        return view(
            'Inventory.InventoryPartNumberCategory02.edit',
            compact('status', 'inventorypartnumbercategory02')
        );
    }

    public function update(Request $request, InventoryPartNumberCategory02 $inventorypartnumbercategory02)
    {
        $inventorypartnumbercategory02->update($request->all());
        return $this->infoResponse('inventorypartnumbercategory02.index');
    }
}
