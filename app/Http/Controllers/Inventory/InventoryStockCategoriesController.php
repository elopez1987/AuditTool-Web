<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\CategoryStock\CreateStockCategoryRequest;
use App\Models\Inventory\Origin\InventoryStockCategory;
use Illuminate\Http\Request;

class InventoryStockCategoriesController extends BaseController
{
    public function index()
    {
        $this->authorize('inventorystockcategories.index');
        $status = $this->getStatusForSchema('STS');

        return view(
            'Inventory.InventoryStockCategory.index',
            compact('status')
        );
    }

    public function getDataStockCategory(Request $request)
    {
        $data = InventoryStockCategory::controlAccessUser()
            ->with(['status:statusid,name', 'center:centerid,name,description', 'warehouse:warehouseid,name'])
            ->Name($request->get('name'))->get();

        return $this->showDataToView(
            $data,
            'Inventory.InventoryStockCategory.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('inventorystockcategories.create');

        return view('Inventory.InventoryStockCategory.create');
    }

    public function store(CreateStockCategoryRequest $request)
    {
        $access = $this->getAccessForUser();
        $category = new InventoryStockCategory($request->validated()
        + ['centerid' => $access->centerid] + ['warehouseid' => $access->warehouseid]);
        $category->save();

        return $this->successResponse('inventorystockcategories.index');
    }

    public function edit(InventoryStockCategory $inventorystockcategory)
    {
        $this->authorize('inventorystockcategories.edit');

        return view(
            'Inventory.InventoryStockCategory.edit',
            compact('inventorystockcategory')
        );
    }

    public function update(Request $request, InventoryStockCategory $inventorystockcategory)
    {
        $inventorystockcategory->update($request->all());

        return $this->redirectBackSuccess();
    }
}
