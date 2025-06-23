<?php

namespace App\Http\Controllers\SafetyTours;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\SafetyTours\CreateTagWebRequest;
use App\Models\SafetyTours\StorageTags;
use Illuminate\Http\Request;

class StorageTagController extends BaseController
{
    public function index()
    {
        $this->authorize('storagetags.index');
        return view('SafetyTours.StorageTags.index');
    }

    public function getDataStorageTags(Request $request)
    {
        $tags = StorageTags::controlAccessUser()
            ->with(['status','center','user'])
            ->Name($request->get('name'))->get();
        return $this->showDataToView($tags, 'SafetyTours.StorageTags.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagetags.create');
        return view('SafetyTours.StorageTags.create');
    }

    public function store(CreateTagWebRequest $request)
    {
        $this->authorize('storagetags.create');
        $tag = new StorageTags($request->validated() + ['centerid' => $this->getCenterForUser()]);
        $tag->save();
        return $this->redirectBackSuccessMessage('Registro guardado con exito.');
    }

    public function edit($taguuid)
    {
        $this->authorize('storagetags.edit');
        $tag = StorageTags::controlAccessUser()->where('taguuid', '=', $taguuid)->first();
        return view('SafetyTours.StorageTags.edit', compact('tag'));
    }

    public function update(Request $request, $taguuid)
    {
        $this->authorize('storagetags.edit');
        $tag = StorageTags::controlAccessUser()->where('taguuid', '=', $taguuid)->first();
        $tag->update($request->all());
        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy($taguuid)
    {
        $this->authorize('storagetags.destroy');
        $tag = StorageTags::controlAccessUser()->where('taguuid', '=', $taguuid)->first();
        $tag->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
