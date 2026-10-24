<?php

namespace App\Http\Controllers\Yard\Destination;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Destination\CreateDestinationRequest;
use App\Models\Yard\Destination\StorageDestination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StorageDestinationController extends BaseController
{
    public function index()
    {
        $this->authorize('storagedestinations.index');

        return view('Yards.StorageDestinations.index');
    }

    public function getDataDestinations(Request $request)
    {
        $data = $this->getData($request);

        return $this->showDataToView(
            $data,
            'Yards.StorageDestinations.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('storagedestinations.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            'Yards.StorageDestinations.create',
            compact('centers', 'warehouses')
        );
    }

    public function store(CreateDestinationRequest $request): RedirectResponse
    {
        $destination = new StorageDestination($request->all());
        $destination->save();

        return $this->successResponse('storagedestinations.index');
    }

    public function edit(StorageDestination $storagedestination)
    {
        $this->authorize('storagedestinations.edit');
        $status = $this->getStatusForSchema('STS');

        return view(
            'Yards.StorageDestinations.edit',
            compact('storagedestination', 'status')
        );
    }

    public function update(StorageDestination $storagedestination, Request $request): RedirectResponse
    {
        $storagedestination->update($request->all());

        return $this->infoResponse('storagedestinations.index');
    }

    private function getData(Request $request)
    {
        return StorageDestination::controlAccessUser()
            ->with('status', 'center', 'warehouse')
            ->Name($request->get('name'))->get();
    }

	public function show(string $storagedestination)
	{

	}

	public function destroy(string $storagedestination)
	{

	}
}
