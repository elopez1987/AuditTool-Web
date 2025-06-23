<?php

namespace App\Http\Controllers\Yard\UnitStatus;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\UnitStatus\CreateUnitStatusRequest;
use App\Models\Yard\StorageUnitStatus;
use Illuminate\Http\Request;

class StorageUnitStatusesController extends BaseController
{

    public function index()
    {
        $this->authorize('storageunitstatus.index');
        $status = $this->getStatusSTS();
        return view('Yards.StorageUnitStatus.index',
            compact('status'));
    }

    public function getDataUnitStatus(Request $request)
    {
        $data = StorageUnitStatus::with(['status'])
            ->Name($request->get('name'))
            ->Status($request->get('statusid'))
            ->get();
        return $this->showDataToView($data, 'Yards.StorageUnitStatus.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storageunitstatus.create');
        return view('Yards.StorageUnitStatus.create');
    }

    public function store(CreateUnitStatusRequest $request)
    {
        $statusunit = new StorageUnitStatus($request->all());
        $statusunit->save();
        return $this->successResponse('storageunitstatus.index');
    }

    public function edit(StorageUnitStatus $storageunitstatus)
    {
        $this->authorize('storageunitstatus.edit');
        $status = $this->getStatusSTS();
        return view('Yards.StorageUnitStatus.edit',
            compact('status', 'storageunitstatus'));
    }

    public function update(Request $request, StorageUnitStatus $storageunitstatus)
    {
        $storageunitstatus->update($request->all());
        return $this->infoResponse('storageunitstatus.index');
    }

}
