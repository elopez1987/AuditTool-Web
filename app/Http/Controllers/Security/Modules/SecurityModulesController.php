<?php

namespace App\Http\Controllers\Security\Modules;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Modules\CreateModule;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Modules\SecurityModule;
use App\Models\Security\Permission\Permission;
use Illuminate\Http\Request;

class SecurityModulesController extends BaseController
{
    public function index()
    {
        $this->authorize('securitymodules.index');

        return view('Security.SecurtiyModules.index');
    }

    public function getDataModules(Request $request)
    {
        $data = SecurityModule::Description($request->get('description'));

        return $this->showDataToView($data, 'Security.SecurtiyModules.partials.buttons');
    }

    public function create()
    {
        $this->authorize('securitymodules.create');

        return view('Security.SecurtiyModules.create');
    }

    public function store(CreateModule $request)
    {
        $module = new SecurityModule($request->validated());
        $module->save();

        return $this->redirectBackSuccess();
    }

    public function edit(SecurityModule $securitymodule)
    {
        $this->authorize('securitymodules.edit');
        $functions = SecurityFunction::where('moduleid', $securitymodule->moduleid)
            ->select(['moduleid', 'functionid', 'description'])->get();

        return view('Security.SecurtiyModules.edit', compact('securitymodule', 'functions'));
    }

    public function update(Request $request, SecurityModule $securitymodule)
    {
        $securitymodule->update($request->all());

        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {
        $functions = SecurityFunction::where('moduleid', $id)->select(['functionid'])->get()->count();
        if ($functions > 0) {
            return $this->errorResponse("Existen funciones relacionadas con este modulo: {$functions}");
        }
        Permission::where('moduleid', $id)->delete();
        SecurityModule::destroy($id);

        return $this->redirectBackSuccess();
    }
}
