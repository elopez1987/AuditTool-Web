<?php

namespace App\Http\Controllers\Shipments\CategoryIncidences;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\CaregoryIncidence\CreateCategoryIncidenceRequest;
use App\Models\Receipt\StorageCategoryIncidence;
use Illuminate\Http\Request;

class StorageCategoryIncidencesController extends BaseController
{
    public function index()
    {
        $this->authorize('storagecategoryincidences.index');

        return view('Shipments.CategoryIncidences.index');
    }

    public function getdatacategoryincidences(Request $request)
    {
        $data = StorageCategoryIncidence::controlAccessUser()
            ->ForDescription($request->input('description'))
            ->leftJoin('systemstatus as se', 'storagecategoryincidences.statusid', '=', 'se.statusid')
            ->leftJoin('systemcenterdistributions as cd', 'storagecategoryincidences.centerid', '=', 'cd.centerid')
            ->leftjoin('systemwarehouses as sw', 'storagecategoryincidences.warehouseid', '=', 'sw.warehouseid')
            ->select(['se.name as status', 'cd.description as center', 'sw.name as warehousename', 'storagecategoryincidences.*']);

        return $this->showDataToView($data, 'Shipments.CategoryIncidences.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagecategoryincidences.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Shipments.CategoryIncidences.create', compact('centers', 'warehouses'));
    }

    public function store(CreateCategoryIncidenceRequest $request)
    {
        $category = new StorageCategoryIncidence($request->all());
        $category->save();

        return redirect('storagecategoryincidences/' . $category->categoryincid . '/adddetail');
    }

    public function edit(StorageCategoryIncidence $storagecategoryincidence)
    {
        $this->authorize('storagecategoryincidences.edit');
        $status = $this->getStatusForSchema('STS');

        return view(
            'Shipments.CategoryIncidences.edit',
            compact('storagecategoryincidence', 'status')
        );
    }

    public function update(Request $request, StorageCategoryIncidence $storagecategoryincidence)
    {
        $storagecategoryincidence->update($request->except('centerid', 'warehouseid'));

        return $this->redirectBackSuccess();
    }
}
