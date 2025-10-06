<?php

namespace App\Models\Security\Roles;

use App\Models\Security\Permission\Permission;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $permission_id
 * @property int $role_id
 * @property-read Permission $permission
 * @property-read \App\Models\Security\Roles\Role $role
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleHasPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleHasPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleHasPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleHasPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleHasPermission whereRoleId($value)
 *
 * @mixin \Eloquent
 */
class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
