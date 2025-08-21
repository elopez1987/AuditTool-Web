<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Inventory\CreateNewInventoryRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\InventoryBox;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Yard\ReceiptUnit\QueryInventoryBox;
use Illuminate\Http\Request;

class InventoryBoxesController extends BaseController
{
    use QueryInventoryBox;

    public function index()
    {
        $this->authorize('inventorybox.index');
        $status = SystemStatus::whereSchemaid('IBX')
            ->select(['statusid', 'name'])->get();
        return view('Yards.InventoryBoxes.index', compact('status'));
    }

    public function getdatainventories(Request $request)
    {
        $data = $this->getInventoryBoxes($request);
        return $this->showDataToView($data, 'Yards.InventoryBoxes.partials.buttons');
    }

    public function create()
    {
        $this->authorize('inventorybox.create');
        $centers = $this->getCenterForSelect();
        $types = SystemStatus::whereSchemaid('INC')->select('name', 'statusid')->get();
        return view('Yards.InventoryBoxes.create', compact('centers', 'types'));
    }

    public function store(CreateNewInventoryRequest $request)
    {
        $total = ReceiptUnit::getCountUnit($request['centerid']);
        $inventory = InventoryBox::createNewInventory($request, $total);
        $this->insertDetailCount($inventory);
        return redirect('inventorybox/' . $inventory->id . '/edit');
    }

    public function edit(InventoryBox $inventorybox)
    {
        $this->authorize('inventorybox.edit');
        $data = $this->getInventoryCountStocks($inventorybox);
        return view('Yards.InventoryBoxes.edit', compact('inventorybox', 'data')
        );
    }

    public function update( InventoryBox $inventorybox)
    {
        $inventorybox->statusid = 'TO';
        $inventorybox->save();
        return $this->redirectBackSuccess();
    }

    public function destroy(InventoryBox $inventorybox)
    {
        $this->authorize('inventorybox.destroy');
        $inventorybox->delete();
        return $this->successResponse('inventorybox.index');
    }

    public function activateInventory(Request $request)
    {
        $this->authorize('inventorybox.edit');
        $inventoryboxActive = InventoryBox::controlAccessUser()
            ->where('statusid', 'AT')->first();
        if (!empty($inventoryboxActive)) {
            $inventoryboxActive->statusid = 'TO';
            $inventoryboxActive->save();
        }
        $inventorybox = InventoryBox::controlAccessUser()
            ->where('id', $request['id'])->first();
        $inventorybox->statusid = 'AT';
        $inventorybox->save();

        return response()->json('Registro actualizado con exito.');
    }


}
