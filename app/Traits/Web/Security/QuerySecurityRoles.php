<?php

namespace App\Traits\Web\Security;

use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Fuctions\SecurityFunctionsMenuStructure;
use App\Models\Security\MenuStructure\SecurityDetailsMenuStructure;
use App\Models\Security\MenuStructure\SecurityMenu;
use App\Models\Security\Modules\SecurityModule;
use App\Models\Security\Roles\Role;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait QuerySecurityRoles
{
    public function getAllRoles($request)
    {
        $user = Auth::user();

        $centers = SecurityAccessWarehouse::where('username', $user->username)
            ->pluck('centerid')
            ->toArray();

        return Role::with([
            'center' => fn ($q) => $q->select('centerid', 'name', 'description'),
            'status' => fn ($qu) => $qu->select('statusid', 'name'),
        ])
            ->Name($request->get('name'))
            ->Role($request->get('roleid'))
            ->ForStatus($request->get('statusid'))
            ->whereIn('roles.centerid', $centers)
            ->when(! $user->hasRole('ADMINISTRADOR'), fn ($q) => $q->where('roles.name', '!=', 'ADMINISTRADOR')
            )
            ->select('roles.*');
    }

    public function getPermissionForRole($id): Collection
    {
        return DB::table('role_has_permissions')
            ->select('permission_id')
            ->where('role_id', $id)->get();
    }

    public function getMenus()
    {
        return SecurityMenu::controlAccessUser()->select(['menustrucid', 'description'])
            ->where('statusid', '=', 'E')->get();
    }

    public function getModulesForRole(Role $securityrole): Collection
    {
        return SecurityModule::whereIn('moduleid', SecurityDetailsMenuStructure::where('menustrucid', $securityrole->menustrucid)->select('moduleid'))
            ->select(['moduleid', 'description', 'auxiliary01'])
            ->get();
    }

    public function getFunctionForRole(Role $securityrole): Collection
    {
        return SecurityFunction::whereIn(
            'functionid',
            SecurityFunctionsMenuStructure::where('menustrucid', $securityrole->menustrucid)
                ->select('functionid')
        )
            ->select(['moduleid', 'functionid', 'description', 'auxiliary01'])
            ->get();
    }

    public function getStructureMenus()
    {
        return SecurityMenu::controlAccessUser()->where('statusid', '=', 'E')
            ->select(['menustrucid', 'description'])->get();
    }
}
