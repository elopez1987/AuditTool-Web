<?php

namespace App;

use App\Models\Configuration\DeviceToken;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\SecurityUsersSession;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string|null $uuiduser
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $username
 * @property string|null $api_token
 * @property string|null $auth0
 * @property string|null $email_verified
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $password_changed_at
 * @property string|null $password_security_log
 * @property-read SecurityAccessWarehouse|null $access
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Client> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $role
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read ModelHasRole|null $roleuser
 * @property-read SecurityUsersSession|null $sessions
 * @property-write mixed $user_name
 * @property-read SystemStatus $status
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User email($email)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User userName($userid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuth0($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordSecurityLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUuiduser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 *
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use HasPermissions;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

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
        'auth0',
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
        'statusid' => 'E',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
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

    // En User.php agrega la relación inversa
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

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
        if (trim($name) != '') {
            $query->where('users.name', 'like', "%{$name}%");
        }
    }

    public function scopeEmail($query, $email)
    {
        if (trim($email) != '') {
            $query->where('users.email', 'like', "%{$email}%");
        }
    }

    public function scopeUserName($query, $userid)
    {
        if (trim($userid) != '') {
            $query->where('users.username', 'like', "%{$userid}%");
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
        if ($statusid != '') {
            $query->where('users.statusid', '=', $statusid);
        } else {
            $query->where('users.statusid', '=', 'E');
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->uuiduser = (string) Str::uuid();
        });
    }
}
