<?php

namespace App\Http\Controllers\SafetyTours;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\SafetyTours\StorageSafetyTourTypeRequest;
use App\Models\SafetyTours\StorageSafetyTourType;
use App\Models\SafetyTours\StorageSafetyTourTypeDetail;
use App\Models\SafetyTours\StorageTags;
use Illuminate\Http\Request;

class StorageSafetyTourTypeController extends BaseController
{
    public function index()
    {
        $this->authorize('storagesafetytourtype.index');
        return view('SafetyTours.StorageSafetyTourType.index');
    }

    public function getDataStorageSafetyTourType(Request $request)
    {
        $this->authorize('storagesafetytourtype.index');
        $data = StorageSafetyTourType::controlAccessUser()
            ->with(['status', 'center', 'user'])
            ->Name($request->get('name'))->get();
        return $this->showDataToView($data, 'SafetyTours.StorageSafetyTourType.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagesafetytourtype.create');
        return view('SafetyTours.StorageSafetyTourType.create');
    }

    public function store(StorageSafetyTourTypeRequest $request)
    {
        $this->authorize('storagesafetytourtype.create');
        $storageSafetyTourType = new StorageSafetyTourType($request->validated() + ['centerid' => $this->getCenterForUser()]);
        $storageSafetyTourType->save();
        return $this->redirectBackSuccessMessage('Registro guardado con exito.');
    }

    public function show($typeuuid)
    {
        $this->authorize('storagesafetytourtype.show');
        $storageSafetyTourType = StorageSafetyTourType::controlAccessUser()->where('typeuuid', '=', $typeuuid)->first();
        $details = StorageSafetyTourTypeDetail::controlAccessUser()->withTrashed()->where('typeid', $storageSafetyTourType->id)->get();
        $detailsExist = StorageSafetyTourTypeDetail::controlAccessUser()
            ->where('typeid', $storageSafetyTourType->id)
            ->pluck('tagid')->toArray();
        $storageTags = StorageTags::controlAccessUser()->where('statusid', '=', 'TAG-01')
            ->select(['id','name'])->whereNotIn('id', $detailsExist)->get();
        return view('SafetyTours.StorageSafetyTourType.show', compact('storageSafetyTourType', 'storageTags', 'details'));
    }

    public function edit(Request $request, $typeuuid)
    {
        $this->authorize('storagesafetytourtype.edit');
        $storageSafetyTourType = StorageSafetyTourType::controlAccessUser()->where('typeuuid', '=', $typeuuid)->first();
        return view('SafetyTours.StorageSafetyTourType.edit', compact('storageSafetyTourType'));
    }

    public function update(StorageSafetyTourTypeRequest $request, $typeuuid)
    {
        $this->authorize('storagesafetytourtype.edit');
        $storageSafetyTourType = StorageSafetyTourType::controlAccessUser()->where('typeuuid', '=', $typeuuid)->first();
        $storageSafetyTourType->update($request->validated());
        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy($typeuuid)
    {
        $this->authorize('storagesafetytourtype.destroy');
        $storageSafetyTourType = StorageSafetyTourType::controlAccessUser()->where('typeuuid', '=', $typeuuid)->first();
        $storageSafetyTourType->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
