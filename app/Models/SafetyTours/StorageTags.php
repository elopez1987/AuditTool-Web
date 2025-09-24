<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property int $id
 * @property string|null $taguuid
 * @property string $tagid
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property int $createuser
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereTagid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereTaguuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTags withoutTrashed()
 * @mixin \Eloquent
 */
class StorageTags extends Model
{
    use softDeletes;
    use LogsActivity;

    protected $table = 'storagetags';

    protected $fillable = [
        'centerid',
        'tagid',
        'taguuid',
        'name',
        'latitude',
        'longitude',
        'createuser',
        'value01',
        'value02',
        'value03',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'TAG-01',
        'schemaid' => 'TAG',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageTags::whereIn('storagetags.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            return $query->where('tagid', 'LIKE', "%{$name}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageTags $model) {
                $model->createuser = Auth::id();
                $model->taguuid = (string) Str::uuid();
            });
        }
    }
}
