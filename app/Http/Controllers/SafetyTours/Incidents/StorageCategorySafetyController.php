<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SafetyTours\Incidents\SaveCategorySafetyRequest;
use App\Models\SafetyTours\Incidents\StorageCategorySafety;
use App\Models\SafetyTours\Incidents\StorageResponsibleArea;
use Illuminate\Http\Request;

class StorageCategorySafetyController extends BaseController
{
    public function index()
    {
        return view('SafetyTours.StorageCategorySafety.index');
    }

    public function getCategoriesSafeties(Request $request)
    {
        $data = StorageCategorySafety::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01', 'center:centerid,name,description'])
            ->select([
                'storage_category_safeties.id',
                'storage_category_safeties.name',
                'storage_category_safeties.created_at',
                'storage_category_safeties.statusid',
                'storage_category_safeties.centerid',
            ])
            ->when($request->input('name'), function ($query) use ($request) {
                $query->where('storage_category_safeties.name', 'like', '%' . $request->name . '%');
            });

        return $this->showDataToView($data, 'SafetyTours.StorageCategorySafety.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();

        return view('SafetyTours.StorageCategorySafety.create', compact('centers'));
    }

    public function store(SaveCategorySafetyRequest $request)
    {
        StorageCategorySafety::create($request->validated());

        return $this->redirectBackSuccessMessage('Categoría de seguridad creada exitosamente');
    }

    public function show(StorageCategorySafety $storage_category_safety)
    {
        $responsible = StorageResponsibleArea::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['id', 'name'])->get();

        return view('SafetyTours.StorageCategorySafety.show',
            compact('storage_category_safety', 'responsible'));
    }

    public function edit(StorageCategorySafety $storage_category_safety)
    {
        return view('SafetyTours.StorageCategorySafety.edit', compact('storage_category_safety'));
    }

    public function update(Request $request, StorageCategorySafety $storage_category_safety)
    {
        $storage_category_safety->update($request->only('name'));

        return $this->redirectBackSuccessMessage('Categoría de seguridad actualizada exitosamente');
    }
}
