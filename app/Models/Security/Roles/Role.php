<?php

namespace App\Models\Security\Roles;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Security\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Role whereUpdatedAt($value)
 *
 * @property string|null $centerid
 * @property string|null $menustrucid
 * @property string|null $description
 * @property string|null $statusid
 * @property string|null $schemaid
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read SystemCenterDistribution|null $center
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role role($roleid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereMenustrucid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'roles';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'name',
        'guard_name',
        'description',
        'statusid',
        'menustrucid',
        'configurationflag',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('roles.name', 'like', "%{$name}%");
        }
    }

    public function scopeRole($query, $roleid)
    {
        if (trim($roleid) != '') {
            $query->where('roles.id', '=', $roleid);
        }
    }

    public function scopeForStatus($query, $statusid)
    {
        if ($statusid != '') {
            $query->where('roles.statusid', '=', $statusid);
        } else {
            $query->where('roles.statusid', '=', 'E');
        }
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return Role::whereIn('roles.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (Role $model) {
                $model->guard_name = 'web';
            });
        }
    }
}
