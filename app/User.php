<?php

namespace App;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\SecurityUsersSession;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    use SoftDeletes;

    protected $fillable = [
        'uuiduser',
        'username',
        'name',
        'email',
        'password',
        'password2',
        'avatar',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'schemaid',
        'password2',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
