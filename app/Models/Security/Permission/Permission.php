<?php

namespace App\Models\Security\Permission;

use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Modules\SecurityModule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

/**
 * App\Models\Security\Permission
 *
 * @method static create(array<string> $array)
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read Collection|array<Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Security\Permission whereUpdatedAt($value)
 * @property int|null $moduleid
 * @property int|null $functionid
 * @property string|null $category
 * @property string|null $description
 * @property-read SecurityFunction|null $function
 * @property-read SecurityModule|null $module
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission description($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission forFunction($functionid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission forModule($moduleid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereFunctionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereModuleid($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    protected $table = 'permissions';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'category',
        'guard_name',
        'description',
        'groupid',
        'moduleid',
        'functionid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

//    public function setNameAttribute($value)
//    {
//        $this->attributes['name'] = strtoupper($value);
//    }

    public function function()
    {
        return $this->belongsTo(SecurityFunction::class, 'functionid');
    }

    public function module()
    {
        return $this->belongsTo(SecurityModule::class, 'moduleid', 'moduleid')->select(['moduleid', 'description']);
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }

    public function scopeForModule($query, $moduleid)
    {
        if ($moduleid !== '') {
            $query->where('moduleid', '=', $moduleid);
        }
    }

    public function scopeForFunction($query, $functionid)
    {
        if ($functionid !== '') {
            $query->where('functionid', $functionid);
        }
    }

    public function scopeDescription($query, $description)
    {
        if (trim($description) !== '') {
            $query->where('description', 'like', "%{$description}%");
        }
    }
}
