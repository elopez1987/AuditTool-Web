<?php

namespace App\Http\Controllers\Configuration\Labels;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Labels\CreateLabels;
use App\Models\Configuration\Labels\InventoryLabel;
use Illuminate\Http\Request;

class InventoryLabelsController extends BaseController
{

    public function index()
    {
        $this->authorize('inventorylabels.index');
        return view('Configuration.InventoryLabels.index');
    }

    public function getDataLabels()
    {
        $data = InventoryLabel::controlAccessUser()->with(['center','status'])->select('inventorylabels.*');
        return $this->showDataToView($data, 'Configuration.InventoryLabels.partials.buttons');
    }

    public function create()
    {
        $this->authorize('inventorylabels.create');
        $centers = $this->getCenterForSelect();
        return view('Configuration.InventoryLabels.create', compact('centers'));
    }

    public function store(CreateLabels $request)
    {
        $label = new InventoryLabel($request->all());
        $label->save();
        return $this->redirectBackSuccess();
    }

    public function edit(InventoryLabel $inventorylabel)
    {
        $this->authorize('inventorylabels.edit');
        $status = $this->getStatusForSchema('STS');
        return view('Configuration.InventoryLabels.edit',
            compact('inventorylabel', 'status'));
    }

    public function update(Request $request, InventoryLabel $inventorylabel)
    {
        $inventorylabel->update($request->all());
        return $this->redirectBackSuccess();
    }
}
