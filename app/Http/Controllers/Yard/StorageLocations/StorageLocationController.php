<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Locations\CreateLocationRequest;
use App\Models\Yard\StorageLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class StorageLocationController extends BaseController
{
    public function index()
    {
        $this->authorize('storagelocations.index');
        $status = $this->getStatusSTS();
        $zones = $this->getActiveZonesForCenter();
        $locations = StorageLocation::controlAccessUser()
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->select(['zoneid', DB::raw('COUNT(id) as total_locations')])
            ->groupBy('zoneid')->get();
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.StorageLocations.index',
            compact('status', 'zones', 'locations','warehouses'));
    }

    public function getDataLocations(Request $request)
    {
        $data = StorageLocation::controlAccessUser()
            ->with(['zone:id,name,zoneid', 'center:centerid,name,description', 'status:statusid,name','warehouse:warehouseid,name'])
            ->Name($request->get('name'))
            ->Zone($request->get('zoneid'))
            ->Status($request->get('statusid'));
        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageLocations.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('storagelocations.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.StorageLocations.create', compact('centers', 'warehouses'));
    }

    public function store(CreateLocationRequest $request): RedirectResponse
    {
        $location = new StorageLocation($request->all());
        $location->save();
        return $this->successResponse('storagelocations.index');
    }

    public function edit(StorageLocation $storagelocation)
    {
        $this->authorize('storagelocations.edit');
        $status = $this->getStatusSTS();
        $zones = $this->getActiveZonesForCenter();
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.StorageLocations.edit',
            compact('storagelocation', 'status', 'zones', 'warehouses'));
    }

    public function update(StorageLocation $storagelocation, Request $request): RedirectResponse
    {
        $storagelocation->update($request->all());
        return $this->infoResponse('storagelocations.index');
    }

    public function destroy($id)
    {
        $location = StorageLocation::findOrFail($id);
        $location->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }


}
