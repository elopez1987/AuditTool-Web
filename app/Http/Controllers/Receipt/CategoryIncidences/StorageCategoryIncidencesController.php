<?php

namespace App\Http\Controllers\Receipt\CategoryIncidences;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Inventory\CreateCategoryIncidenceRequest;
use App\Models\Receipt\StorageCategoryIncidence;
use Illuminate\Http\Request;

class StorageCategoryIncidencesController extends BaseController
{
    public function index()
    {
        $this->authorize('storagecategoryincidences.index');

        return view('Receipt.CategoryIncidences.index');
    }

    public function getdatacategoryincidences(Request $request)
    {
        $data = StorageCategoryIncidence::controlAccessUser()
            ->leftJoin('systemstatus as se', 'storagecategoryincidences.statusid', '=', 'se.statusid')
            ->leftJoin('systemcenterdistributions as cd', 'storagecategoryincidences.centerid', '=', 'cd.centerid')
            ->select(['se.name as status',
                'cd.description as center',
                'storagecategoryincidences.id',
                'storagecategoryincidences.centerid',
                'storagecategoryincidences.description',
                'storagecategoryincidences.statusid',
                'storagecategoryincidences.auxiliary01',
            ]);

        return $this->showDataToView($data, 'Receipt.CategoryIncidences.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagecategoryincidences.create');
        $centers = $this->getCenterForSelect();

        return view('Receipt.CategoryIncidences.create', compact('centers'));
    }

    public function store(CreateCategoryIncidenceRequest $request)
    {
        $category = new StorageCategoryIncidence($request->validated());
        $category->save();

        return $this->redirectBackSuccessMessage('Categoria registrada con exito.');
        //        return redirect('storagecategoryincidences/'.$category->categoryincid.'/adddetail');
    }

    public function edit(StorageCategoryIncidence $categories_incident)
    {
        $this->authorize('storagecategoryincidences.edit');
        $status = $this->getStatusForSchema('STS');

        return view(
            'Receipt.CategoryIncidences.edit',
            compact('categories_incident', 'status'),
        );
    }

    public function update(Request $request, StorageCategoryIncidence $categories_incident)
    {
        $categories_incident->update($request->except('centerid', 'warehouseid'));

        return $this->redirectBackSuccess();
    }
}
