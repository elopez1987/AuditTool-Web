<?php

namespace App\Http\Controllers\Configuration\NameSystem;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\NameSystem\NameSystem;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NameSystemController extends BaseController
{
    use EvidencePhotoTrait;

    public function index()
    {
        $this->authorize('namesystem.index');
        $names = NameSystem::get();
        return view('Configuration.NameSystem.index', compact('names'));
    }

    public function create()
    {
        $this->authorize('namesystem.create');
        return view('Configuration.NameSystem.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $name = new NameSystem($request->all());
        $name->save();
        return $this->successResponse('namesystem.index');
    }

    public function edit(NameSystem $namesystem)
    {
        $this->authorize('namesystem.edit');
        return view('Configuration.NameSystem.edit', compact('namesystem'));
    }

    public function update(Request $request, NameSystem $namesystem): RedirectResponse
    {
        $namesystem->update($request->all());
        if (Storage::exists('storage/' . $namesystem->auxiliary05)) {
            Storage::delete('storage/' . $namesystem->auxiliary05);
        }
        if ($request->hasFile('file')) {
            $path = $this->addMediaToModel($request, 'ImageProfile');
            $namesystem->auxiliary05 = $path;
            $namesystem->save();
        }
        return $this->infoResponse('namesystem.index');
    }
}
