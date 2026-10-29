<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryPartNumber\CreatePartNumberRequest;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InventoryPartnumbersController extends BaseController
{
    public function index()
    {
        $this->authorize('inventorypartnumbers.index');

        return view('Inventory.InventoryPartnumbers.index');
    }

    public function getDataInventoryNumbers(Request $request)
    {
        $data = InventoryPartnumber::controlAccessUser()
            ->with(['status:statusid,name', 'center:centerid,name,description',
                'warehouse:warehouseid,name', 'category01:id,description', 'category02:id,description'])
            ->PartNumber($request->get('prtnum'))
            ->Status($request->get('statusid'));

        return $this->showDataToView($data, 'Inventory.InventoryPartnumbers.partials.buttons');
    }

    public function create()
    {
        $this->authorize('inventorypartnumbers.create');
        $category01 = $this->getCategoryInv01();
        $category02 = $this->getCategoryInv02();
        $units = InventoryUnitMeasure::controlAccessUser()->where('statusid', 'E')->get();
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            'Inventory.InventoryPartnumbers.create',
            compact('category01', 'category02', 'units', 'centers', 'warehouses')
        );
    }

    public function store(CreatePartNumberRequest $request)
    {

        $partnumber = new InventoryPartnumber($request->validated());
        $partnumber->save();

        return $this->redirectBackSuccess();
    }

    public function edit(InventoryPartnumber $inventorypartnumber)
    {
        $this->authorize('inventorypartnumbers.edit');
        $category01 = $this->getCategoryInv01();
        $category02 = $this->getCategoryInv02();
        $status = $this->getStatusForSchema('STS');
        $units = InventoryUnitMeasure::controlAccessUser()->where('statusid', 'E')->get();

        return view(
            'Inventory.InventoryPartnumbers.edit',
            compact('inventorypartnumber', 'status', 'category02', 'category01', 'units')
        );
    }

    public function update(Request $request, InventoryPartnumber $inventorypartnumber)
    {
        $requestData = $request->all();

        $safeData = collect($requestData)
            ->filter(fn ($value, $column) => Schema::hasColumn($inventorypartnumber->getTable(), $column)
            )
            ->toArray();

        if (! empty($safeData)) {
            $inventorypartnumber->update($safeData);
        }

        return $this->redirectBackSuccess();
    }

    public function show(string $inventorypartnumber) {}

    private function getCategoryInv01(): Collection
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory01::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select('description', 'id')->get();
    }

    private function getCategoryInv02(): Collection
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory02::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select(['description', 'id'])->get();
    }
}
