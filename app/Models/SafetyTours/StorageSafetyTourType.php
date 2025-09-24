<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property int $id
 * @property string|null $typeuuid
 * @property string $name
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property int $userid
 * @property string $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereTypeuuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourType withoutTrashed()
 * @mixin \Eloquent
 */
class StorageSafetyTourType extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'storagesafetytourtypes';

    protected $fillable = [
        'centerid', 'name', 'typeuuid', 'userid', 'statusid', 'value01', 'value02', 'value03',
    ];

    protected $attributes = [
        'statusid' => 'TYP-01',
        'schemaid' => 'TYP',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTourType::whereIn('storagesafetytourtypes.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            return $query->where('name', 'LIKE', "%{$name}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSafetyTourType $model) {
                $model->userid = Auth::id();
                $model->typeuuid = (string) Str::uuid();
            });
        }
    }
}
