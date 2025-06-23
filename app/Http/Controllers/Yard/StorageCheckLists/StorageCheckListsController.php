<?php

namespace App\Http\Controllers\Yard\StorageCheckLists;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\CheckList\CreateCheckListRequest;
use App\Models\Yard\StorageCheckList;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageCheckListsController extends BaseController
{

    public function index()
    {
        $this->authorize('storagechecklists.index');
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.StorageCheckLists.index',compact('warehouses'));
    }

    public function getDataCheckList(Request $request)
    {
        $data = StorageCheckList::controlAccessUser()
            ->with('center:centerid,description','status:statusid,name')
            ->Name($request->get('name'))
            ->Warehouse($request->get('warehouseid'))
            ->get();
        return DataTables::of($data)
            ->addColumn('btn','Yards.StorageCheckLists.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->warehouse ? $row->warehouse->name : '';
            })
            ->rawColumns(['btn','warehousename'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('storagechecklists.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.StorageCheckLists.create', compact('centers','warehouses'));
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
        return view('Yards.StorageCheckLists.edit', compact('storagechecklist', 'status','warehouses'));
    }

    public function update(Request $request, StorageCheckList $storagechecklist)
    {
        $storagechecklist->update($request->all());
        return $this->infoResponse('storagechecklists.index');
    }

}
