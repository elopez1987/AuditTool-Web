<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Locations\CreateLocationRequest;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageLocationController extends BaseController
{
    public function index()
    {
        $this->authorize('storagelocations.index');
        $status = $this->getStatusSTS();
        $zones = StorageZone::controlAccessUser()->where('statusid', 'E')
            ->select(['id', 'name'])->get();

        return view('Yards.StorageLocations.index',
            compact('status', 'zones'));
    }

    public function getDataLocations(Request $request)
    {

        $query = StorageLocation::withTrashed()
            ->with([
                'zone:id,name,zoneid',
                'status:statusid,name,auxiliary01',
            ])
            ->select('storagelocations.*')
            ->when($request->input('name'), function ($query, $name) {
                $query->where('storagelocations.name', 'LIKE', '%'.$name.'%');
            })
            ->when($request->input('zoneid'), function ($query, $zoneid) {
                $query->where('storagelocations.zoneid', $zoneid);
            })
            ->when($request->input('statusid'), function ($query, $statusid) {
                $query->where('storagelocations.statusid', $statusid);
            });

        return DataTables::eloquent($query)
            ->addColumn('btn', 'Yards.StorageLocations.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('storagelocations.create');
        $zones = StorageZone::controlAccessUser()->where('statusid', 'E')
            ->select(['id', 'name'])->get();

        return view('Yards.StorageLocations.create', compact('zones'));
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

        return view(
            'Yards.StorageLocations.edit',
            compact('storagelocation', 'status', 'zones', 'warehouses'),
        );
    }

    public function update(StorageLocation $storagelocation, Request $request): RedirectResponse
    {
        $storagelocation->update($request->all());

        return $this->infoResponse('storagelocations.index');
    }

    public function destroy($id, Request $request)
    {
        try {
            $location = StorageLocation::withTrashed()->findOrFail($id);

            $isDeleted = $location->trashed();
            $isDeleted ? $location->restore() : $location->delete();

            $message = $isDeleted ? 'Registro restaurado con éxito.' : 'Registro eliminado con éxito.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el registro: '.$e->getMessage(),
                ], 500);
            }
        }
    }

    public function show(string $storagelocation) {}
}
