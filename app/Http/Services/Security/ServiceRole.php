<?php

namespace App\Http\Services\Security;

use App\Models\Security\Roles\Role;
use App\Models\Security\Roles\RoleHasPermission;
use App\Traits\Web\Security\QuerySecurityRoles;
use Illuminate\Http\Request;

class ServiceRole
{
    use QuerySecurityRoles;

    public function syncPermissionsToRole(Request $request, Role $securityrole): void
    {
        $incomingPermissions = collect($request->input('permissions', []))->filter()->map(fn ($id) => (int) $id);

        $currentPermissions = RoleHasPermission::where('role_id', $securityrole->id)
            ->pluck('permission_id');

        $toAdd = $incomingPermissions->diff($currentPermissions);
        $toRemove = $currentPermissions->diff($incomingPermissions);

        if ($toRemove->isNotEmpty()) {
            RoleHasPermission::where('role_id', $securityrole->id)
                ->whereIn('permission_id', $toRemove)
                ->delete();
        }

        if ($toAdd->isNotEmpty()) {
            $records = $toAdd->map(fn ($permissionId) => [
                'role_id' => $securityrole->id,
                'permission_id' => $permissionId,
            ])->values()->toArray();

            RoleHasPermission::insert($records);
        }
    }
}
