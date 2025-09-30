<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $touruuid
 * @property string $centerid
 * @property string $name
 * @property string $datetours
 * @property int $sequence
 * @property int $userid
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereDatetours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereTouruuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTours whereValue03($value)
 * @mixin \Eloquent
 */
class StorageSafetyTours extends Model
{
    use LogsActivity;

    protected $table = 'storagesafetytours';
    protected $fillable = [
        'centerid',
        'touruuid',
        'name',
        'datetours',
        'sequence',
        'userid',
        'value01',
        'value02',
        'value03',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'RSE-01',
        'schemaid' => 'RSE',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTours::whereIn('storagesafetytours.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    //    Filtros de busqueda
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            return $query->where('touruuid', 'LIKE', "%{$name}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSafetyTours $model) {
                $model->userid = Auth::id();
                $model->touruuid = (string) Str::uuid();
            });
        }
    }
}
