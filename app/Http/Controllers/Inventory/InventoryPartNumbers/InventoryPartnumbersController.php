<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryPartNumber\CreatePartNumberRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Http\Request;

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
            ->with(['status', 'center', 'warehouse', 'category01', 'category02'])
            ->PartNumber($request->get('prtnum'))
            ->Status($request->get('statusid'))
            ->select(['inventorypartnumbers.*',]);;
        return $this->showDataToView($data, 'Inventory.InventoryPartnumbers.partials.buttons');
    }

    public function create()
    {
        $this->authorize('inventorypartnumbers.create');
        $category01 = $this->getCategoryInv01();
        $category02 = $this->getCategoryInv02();
        $units = InventoryUnitMeasure::controlAccessUser()->where('statusid','E')->get();
         return view('Inventory.InventoryPartnumbers.create',
            compact('category01', 'category02','units'));
    }

    public function store(CreatePartNumberRequest $request)
    {
        $access = $this->getAccessForUser();
        $sku = InventoryPartnumber::controlAccessUser()
            ->where('prtnum', $request['prtnum'])->first();
        if (!empty($sku)) {
            return $this->errorResponse('El número de parte ya existe.');
        }

        $partnumber = new InventoryPartnumber($request->all() +
            ['centerid' => $access->centerid] +
            ['warehouseid' => $access->warehouseid]);
        $partnumber->save();
        return $this->redirectBackSuccess();
    }

    public function edit(InventoryPartnumber $inventorypartnumber)
    {
        $this->authorize('inventorypartnumbers.edit');
        $category01 = $this->getCategoryInv01();
        $category02 = $this->getCategoryInv02();
        $status = $this->getStatusForSchema('STS');
        $units = InventoryUnitMeasure::controlAccessUser()->where('statusid','E')->get();
        return view('Inventory.InventoryPartnumbers.edit',
            compact('inventorypartnumber', 'status', 'category02', 'category01','units'));
    }

    public function update(Request $request, InventoryPartnumber $inventorypartnumber)
    {
        $inventorypartnumber->update($request->all());
        return $this->redirectBackSuccess();
    }



}
