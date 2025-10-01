<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property \App\Models\Maintenance\StorageLiftTruck $serialnumber
 * @property string $horometro
 * @property string $distance
 * @property string $stationary
 * @property int $userid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereHorometro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereStationary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUsageDataForkLift whereValue03($value)
 * @mixin \Eloquent
 */
class StorageUsageDataForkLift extends Model
{
    use LogsActivity;

    protected $table = 'storageusagedataforklift';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'horometro',
        'distance',
        'stationary',
        'userid',
        'value01',
        'value02',
        'value03',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageUsageDataForkLift::whereIn('storageusagedataforklift.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function serialnumber(): BelongsTo
    {
        return $this->belongsTo(StorageLiftTruck::class, 'serialnumber');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageUsageDataForkLift $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
