<?php

namespace App\Models\Security\Roles;

use App\Models\Security\Permission\Permission;
use Illuminate\Database\Eloquent\Model;

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
