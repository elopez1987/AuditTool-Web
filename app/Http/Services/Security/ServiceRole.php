<?php

namespace App\Http\Services\Security;

use App\Traits\Web\Security\QuerySecurityRoles;
use Illuminate\Support\Facades\DB;

class ServiceRole
{
    use QuerySecurityRoles;

    public function destroyPermissionsToRole($securityrole): void
    {
        DB::table('role_has_permissions')
            ->where('role_id', '=', $securityrole->id)->delete();
    }

    public function createNewPermissionToRole($request, $securityrole): void
    {
        if ($request['permissions'] != "") {
            $total = count($request["permissions"]) - 1;
            for ($i = 0; $i <= $total; $i++) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $securityrole->id,
                    'permission_id' => $request['permissions'][$i]
                ]);
            }
        }
    }


}
