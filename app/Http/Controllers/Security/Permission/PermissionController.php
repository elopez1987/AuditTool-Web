<?php

namespace App\Http\Controllers\Security\Permission;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Permission\CreatePermissionRequest;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Modules\SecurityModule;
use App\Models\Security\Permission\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    public function index()
    {
        $this->authorize('securitypermissions.index');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.Permissions.index', compact('modules'));
    }

    public function getDataPermissions(Request $request)
    {
        $data = Permission::with(['module:moduleid,description', 'function:functionid,description'])
            ->ForModule($request->get('moduleid'))
            ->ForFunction($request->get('functionid'))
            ->Name($request->get('name'))
            ->Description($request->get('description'))->get();

        return $this->showDataToView($data, 'Security.Permissions.partials.buttons2');
    }

    public function create()
    {
        $this->authorize('securitypermissions.create');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.Permissions.create', compact('modules'));
    }

    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated() + ['guard_name' => 'web']);

        return $this->redirectBackSuccess();
    }

    public function edit(Permission $securitypermission)
    {
        $this->authorize('securitypermissions.edit');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();
        $functions = SecurityFunction::select(['description', 'functionid'])->get();

        return view(
            'Security.Permissions.edit',
            compact('securitypermission', 'functions', 'modules')
        );
    }

    public function update(Request $request, Permission $securitypermission)
    {
        $securitypermission->update($request->all());

        return $this->redirectBackSuccess();
        //        return redirect('securityfunctions/' . $securitypermission->functionid . '/edit');
    }

    public function destroy($id)
    {
        $this->authorize('securitypermissions.destroy');
        Permission::destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con exito!!');
    }
}
