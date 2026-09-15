<?php

namespace App\Http\Controllers\Security\Roles;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Roles\CreateRoleRequest;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Security\Permission\Permission;
use App\Models\Security\Roles\Role;
use App\Models\Security\Roles\RoleHasPermission;
use App\Services\Security\ServiceRole;
use App\Traits\Web\Security\QuerySecurityRoles;
use Artisan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    use QuerySecurityRoles;

    private ServiceRole $serviceRole;

    public function __construct(ServiceRole $serviceRole)
    {
        $this->serviceRole = $serviceRole;
    }

    public function index()
    {
        $this->authorize('securityroles.index');
        $status = $this->getStatusForSchema('STS');
        $roles = Role::controlAccessUser()->select(['description', 'id'])->get();

        return view('Security.Roles.index', compact('roles', 'status'));
    }

    public function getDataRoles(Request $request)
    {
        $data = $this->getAllRoles($request);

        return $this->showDataToView($data, 'Security.Roles.partials.buttons');
    }

    public function create()
    {
        $this->authorize('securityroles.create');
        $centers = $this->getCenterForSelect();
        $menuStrucs = $this->getMenus();

        return view('Security.Roles.create', compact('centers', 'menuStrucs'));
    }

    public function store(CreateRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->save();

        return redirect('securityroles/' . $role->id . '/edit');
    }

    public function edit(Role $securityrole)
    {
        $this->authorize('securityroles.edit');
        $assings = RoleHasPermission::where('role_id', $securityrole->id)->get();
        $modules = $this->getModulesForRole($securityrole);
        $functions = $this->getFunctionForRole($securityrole);
        $permissions = Permission::select(['id', 'name', 'functionid', 'description'])->get();
        $access = $this->getPermissionForRole($securityrole->id);
        $status = $this->getStatusForSchema('STS');
        $menus = $this->getStructureMenus();
        $checkMtn = SystemDetailParameter::where('value02', $securityrole->id)->where('parameterid', 'ROLE-TRUCK')->count();

        return view('Security.Roles.edit', compact(
            'securityrole',
            'permissions',
            'access',
            'modules',
            'functions',
            'assings',
            'status',
            'menus',
            'checkMtn'
        ));
    }

    public function update(Request $request, Role $securityrole): RedirectResponse
    {
        $securityrole->update($request->all());

        return $this->redirectBackSuccess();
    }

    public function destroy(Role $securityrole)
    {
        $this->authorize('securityroles.destroy');
        $securityrole->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado con exito!!');
    }

    public function updatePermissionToRole(Request $request, Role $securityrole)
    {
        $this->serviceRole->syncPermissionsToRole($request, $securityrole);
        Artisan::call('optimize:clear');

        return $this->redirectBackSuccess();
    }
}
