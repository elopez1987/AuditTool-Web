<?php

namespace App;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\SecurityUsersSession;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasApiTokens;
    use Notifiable;
    use LogsActivity;
    use HasPermissions;
    use HasRoles;
    use SoftDeletes;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }


    protected $fillable = [
        'uuiduser',
        'username',
        'name',
        'email',
        'password',
        'password_changed_at',
        'avatar',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
		'statusid',
		'auth0'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'schemaid',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_at' => 'datetime:d/m/Y H:s',
        'lastlogin' => 'datetime:d/m/Y H:s',
        'configurationflag' => 'array',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->uuiduser = (string) Str::uuid();
        });
    }



    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setUserNameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

//    Relaciones entre modelos
    public function sessions()
    {
        return $this->hasOne(SecurityUsersSession::class, 'username', 'username');
    }
    public function access()
    {
        return $this->belongsTo(SecurityAccessWarehouse::class, 'username', 'username');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function roleuser()
    {
        return $this->belongsTo(ModelHasRole::class, 'id', 'model_id');
    }


//    Filtros de consultas
    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('users.name', 'like', "%$name%");
        }
    }
    public function scopeEmail($query, $email)
    {
        if (trim($email) != "") {
            $query->where('users.email', 'like', "%$email%");
        }
    }
    public function scopeUserName($query, $userid)
    {
        if (trim($userid) != "") {
            $query->where('users.username', 'like', "%$userid%");
        }
    }
    public function ScopeRole($query, $role_id)
    {
        if (trim($role_id) != '') {
            $query->where('roles.id', $role_id);
        }
    }
    public function scopeForStatus($query, $statusid)
    {
        if ($statusid != "") {
            $query->where('users.statusid', '=', $statusid);
        } else {
            $query->where('users.statusid', '=', 'E');
        }
    }


}
