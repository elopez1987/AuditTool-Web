<?php

namespace App\Http\Controllers\Yard\StorageCheckLists;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\CheckList\CreateCheckListRequest;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageReason;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageCheckListsController extends BaseController
{
    public function index()
    {
        $this->authorize('storagechecklists.index');
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.StorageCheckLists.index', compact('warehouses'));
    }

    public function getDataCheckList(Request $request)
    {
        $data = StorageCheckList::controlAccessUser()
            ->with(['center:centerid,description', 'status:statusid,name'])
            ->leftJoin('storagereasons', 'storagereasons.id', '=', 'storagechecklists.auxiliary03')
            ->leftJoin('storageunittypes', 'storageunittypes.auxiliary01', '=', 'storagechecklists.auxiliary01')
            ->Name($request->get('name'))
            ->Warehouse($request->get('warehouseid'))
            ->select('storagechecklists.*', 'storagereasons.name as reason', 'storageunittypes.description as unittype')
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageCheckLists.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->warehouse ? $row->warehouse->name : '';
            })
            ->rawColumns(['btn', 'warehousename'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('storagechecklists.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $concetps = StorageReason::where('auxiliary01', '1')
            ->select('id', 'name')->get();

        return view('Yards.StorageCheckLists.create', compact('centers', 'warehouses', 'concetps'));
    }

    public function store(CreateCheckListRequest $request)
    {
        $checklist = new StorageCheckList($request->validated());
        $checklist->save();

        return $this->successResponse('storagechecklists.index');
    }

    public function edit(StorageCheckList $storagechecklist)
    {
        $this->authorize('storagechecklists.edit');
        $status = $this->getStatusSTS();
        $warehouses = $this->getWarehouseForSelect();
        $concetps = StorageReason::where('auxiliary01', '1')
            ->select('id', 'name')->get();

        return view('Yards.StorageCheckLists.edit',
            compact('storagechecklist', 'status', 'warehouses', 'concetps'));
    }

    public function update(Request $request, StorageCheckList $storagechecklist)
    {
        $storagechecklist->update($request->all());

        return $this->infoResponse('storagechecklists.index');
    }
}
