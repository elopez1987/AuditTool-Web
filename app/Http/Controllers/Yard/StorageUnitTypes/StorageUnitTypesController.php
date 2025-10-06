<?php

namespace App\Http\Controllers\Yard\StorageUnitTypes;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Yard\StorageUnitTypesRequest;
use App\Models\Yard\StorageUnitType;
use Illuminate\Http\Request;

class StorageUnitTypesController extends BaseController
{
    public function index()
    {
        $this->authorize('storageunittypes.index');

        return view('Yards.StorageUnitTypes.index');
    }

    public function getStorageUnitTypes(Request $request)
    {
        $data = StorageUnitType::controlAccessUser()
            ->Name($request->get('name'))
            ->with('center', 'status')->get();

        return $this->showDataToView($data, 'Yards.StorageUnitTypes.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storageunittypes.create');
        $centers = $this->getCenterForSelect();

        return view('Yards.StorageUnitTypes.create', compact('centers'));
    }

    public function store(StorageUnitTypesRequest $request)
    {
        StorageUnitType::create($request->validated());

        return $this->successResponse('storageunittypes.index');
    }

    public function edit($id)
    {
        $this->authorize('storageunittypes.edit');
        $model = StorageUnitType::controlAccessUser()->findOrFail($id);

        return view('Yards.StorageUnitTypes.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = StorageUnitType::controlAccessUser()->findOrFail($id);
        $model->update($request->except('centerid'));

        return $this->infoResponse('storageunittypes.index');
    }
}
