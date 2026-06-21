<?php

namespace App\Http\Controllers\Yard\StorageUnitTypes;

use App\Http\Controllers\BaseController;
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
            ->with(['center:centerid,name,description', 'status:statusid,name,auxiliary01'])
            ->Name($request->input('name'))
            ->select([
                'storageunittypes.id',
                'storageunittypes.centerid',
                'storageunittypes.description',
                'storageunittypes.statusid',
                'storageunittypes.auxiliary01',
                'storageunittypes.auxiliary02',
                'storageunittypes.auxiliary03',
                'storageunittypes.auxiliary04',
                'storageunittypes.auxiliary05',
                'storageunittypes.unittypeid',
            ]);

        return $this->showDataToView($data, 'Yards.StorageUnitTypes.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storageunittypes.create');
        $centers = $this->getCenterForSelect();

        return view('Yards.StorageUnitTypes.create', compact('centers'));
    }

    public function store(Request $request)
    {
        StorageUnitType::create($request->all());

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

    public function destroy(Request $request)
    {
        $this->authorize('storageunittypes.destroy');
        $model = StorageUnitType::controlAccessUser()->findOrFail($request->id);
        $model->delete();

        return $this->responseSuccessAjax('', now());
    }
}
