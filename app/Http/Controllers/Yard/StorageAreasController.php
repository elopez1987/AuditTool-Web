<?php

namespace App\Http\Controllers\Yard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Areas\StorageAreasRequest;
use App\Models\Yard\StorageAreas;
use Illuminate\Http\Request;

class StorageAreasController extends BaseController
{
    public function index()
    {
        $this->authorize('storageareas.index');
        $status = $this->getStatusSTS();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageAreas.index', compact('status', 'warehouses'));
    }

    public function getDataAreas(Request $request)
    {
        $data = StorageAreas::controlAccessUser()->withTrashed()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,name,description', 'warehouse:warehouseid,name'])
            ->select([
                'storageareas.id',
                'storageareas.slug',
                'storageareas.centerid',
                'storageareas.warehouseid',
                'storageareas.name',
                'storageareas.statusid',
                'storageareas.auxiliary01',
                'storageareas.auxiliary02',
                'storageareas.auxiliary03',
            ])
            ->Name($request->input('name'))
            ->Status($request->input('statusid'))
            ->ForWarehouse($request->input('warehouseid'));

        return $this->showDataToView($data, 'Yards.StorageAreas.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageAreas.create', compact('centers', 'warehouses'));
    }

    public function store(StorageAreasRequest $request)
    {
        StorageAreas::create($request->validated());

        return $this->redirectBackSuccessMessage('Area de almacen creada con exito');
    }

    public function edit($id)
    {
        $storageAreas = StorageAreas::controlAccessUser()->where('slug', $id)->first();
        if (! $storageAreas) {
            return $this->errorResponse('Area de almacen no encontrada');
        }
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $status = $this->getStatusSTS();

        return view(
            'Yards.StorageAreas.edit',
            compact('storageAreas', 'centers', 'warehouses', 'status')
        );
    }

    public function update(StorageAreasRequest $request, $id)
    {
        $storageAreas = StorageAreas::controlAccessUser()->where('slug', $id)->first();
        if (! $storageAreas) {
            return $this->errorResponse('Area de almacen no encontrada');
        }
        $storageAreas->update($request->validated());

        return $this->redirectBackSuccessMessage('Area de almacen actualizada con exito');
    }

    public function destroy(Request $request, $id)
    {
        $storageAreas = StorageAreas::controlAccessUser()
            ->withTrashed()->where('slug', $id)->first();
        if ($storageAreas->statusid === 'E') {
            $storageAreas->statusid = 'D';
            $storageAreas->save();
            $storageAreas->delete();

            return $this->redirectBackSuccessMessage('Area de almacen eliminada con exito');
        }
        if ($storageAreas->statusid === 'D') {
            $storageAreas->statusid = 'E';
            $storageAreas->save();
            $storageAreas->restore();

            return $this->redirectBackSuccessMessage('Area de almacen restaurada con exito');
        }
    }
}
