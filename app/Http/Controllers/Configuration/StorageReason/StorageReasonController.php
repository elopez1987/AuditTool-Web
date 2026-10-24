<?php

namespace App\Http\Controllers\Configuration\StorageReason;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CreateReasonRequest;
use App\Models\Yard\StorageReason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StorageReasonController extends BaseController
{
    public function index()
    {
        $this->authorize('storagereasons.index');

        return view('Configuration.StorageReasons.index');
    }

    public function getDataTypeMovements(Request $request)
    {
        $data = StorageReason::Name($request->get('name'))
            ->select('storagereasons.*');

        return $this->showDataToView($data, 'Configuration.StorageReasons.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagereasons.create');

        return view('Configuration.StorageReasons.create');
    }

    public function store(CreateReasonRequest $request): RedirectResponse
    {
        StorageReason::create($request->validated());

        return $this->successResponse('storagereasons.index');
    }

    public function edit(StorageReason $storagereason)
    {
        $this->authorize('storagereasons.edit');
        $status = $this->getStatusSTS();

        return view('Configuration.StorageReasons.edit', compact('storagereason', 'status'));
    }

    public function update(StorageReason $storagereason, Request $request): RedirectResponse
    {
        $storagereason->update($request->all());

        return $this->infoResponse('storagereasons.index');
    }
}
