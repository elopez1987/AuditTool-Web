<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $id
 * @property string $name
 * @property string $startdate
 * @property string $finishdate
 * @property string $schemaid
 * @property string $statusid
 * @property int $createuser
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverse whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ScheduleInverse extends Model
{
    use LogsActivity;

    protected $table = 'scheduleinverse';

    protected $fillable = [
        'centerid', 'warehouseid', 'createuser',
        'name', 'startdate', 'finishdate', 'statusid',
    ];

    //INO = Inactivo
    //ACT = Activo
    protected $attributes = [
        'statusid' => 'INO',
        'schemaid' => 'KLD',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ScheduleInverse::whereIn('scheduleinverse.warehouseid', $warehouses)
            ->whereIn('scheduleinverse.centerid', $centers);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ScheduleInverse $model) {
                $model->createuser = Auth::id();
            });
        }
    }
}
