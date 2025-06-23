<?php

namespace App\Traits\Web\Security;

use App\Models\Security\MenuStructure\SecurityMenu;
use App\Models\Security\Roles\Role;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait QuerySecurityRoles
{
    public function getAllRoles($request)
    {
        $centers = (new SecurityAccessWarehouse())
            ->where('username', Auth::user()->username)
            ->select('centerid')->get()->toArray();

        $roles = Role::with([
            'center' => function ($q) {
                $q->select('centerid', 'name', 'description');
            },
            'status' => function ($qu) {
                $qu->select('statusid', 'name');
            }
        ])
            ->Name($request->get('name'))
            ->Role($request->get('roleid'))
            ->ForStatus($request->get('statusid'))
            ->whereIn('roles.centerid', $centers)
            ->select('roles.*');
        return $roles;
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

    public function getModulesForRole($securityrole)
    {
        return DB::select("select * from securitymodules
                        where moduleid in (select moduleid from securitydetailsmenustructure where menustrucid =?)
                    ", [$securityrole->menustrucid]);
    }

    public function getFunctiondForRole($securityrole)
    {
        return DB::select("select * from securityfunctions s
                                    where functionid in (select functionid from securityfunctionsmenustructure
                                        s2 where menustrucid=?)", [$securityrole->menustrucid]);
    }

    public function getStructureMenus()
    {
        return SecurityMenu::controlAccessUser()->where('statusid','=','E')
            ->select(['menustrucid','description'])->get();
    }


}
