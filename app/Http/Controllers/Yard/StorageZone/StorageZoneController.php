<?php

namespace App\Http\Controllers\Yard\StorageZone;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Zones\CreateZoneRequest;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageZoneController extends BaseController
{
    public function index()
    {
        $this->authorize('storagezones.index');
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageZones.index', compact('warehouses'));
    }

    public function getDataZones(Request $request)
    {
        $data = StorageZone::controlAccessUser()
            ->with(['center:centerid,name,description', 'status:statusid,name,auxiliary01'])
            ->withTrashed()
            ->select([
                'storagezones.id',
                'storagezones.centerid',
                'storagezones.warehouseid',
                'storagezones.name',
                'storagezones.zoneid',
                'storagezones.statusid',
                'storagezones.auxiliary01',
                'storagezones.capacity',
                'storagezones.deleted_at',
            ])
            ->Name($request->input('name'))
            ->Warehouse($request->input('warehouseid'));

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageZones.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->warehouse ? $row->warehouse->name : '';
            })->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('storagezones.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageZones.create', compact('centers', 'warehouses'));
    }

    public function store(CreateZoneRequest $request): RedirectResponse
    {
        $zone = new StorageZone($request->all());
        $zone->save();

        return $this->successResponse('storagezones.index');
    }

    public function edit(StorageZone $storagezone)
    {
        $this->authorize('storagezones.edit');
        $status = $this->getStatusSTS();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageZones.edit', compact('status', 'storagezone', 'warehouses'));
    }

    public function update(StorageZone $storagezone, Request $request): RedirectResponse
    {
        $storagezone->update($request->all());

        return $this->infoResponse('storagezones.index');
    }

    public function destroy(Request $request)
    {
        try {
            $this->authorize('storagezones.destroy');
            $zone = StorageZone::controlAccessUser()->withTrashed()->findOrFail($request->id);

            $isDeleted = $zone->trashed();
            $isDeleted ? $zone->restore() : $zone->delete();

            $message = $isDeleted ? 'Registro restaurado con éxito.' : 'Registro eliminado con éxito.';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el registro: ' . $th->getMessage(),
            ], 500);
        }
    }
}
