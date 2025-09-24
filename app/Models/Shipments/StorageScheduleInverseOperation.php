<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property int $inverseid
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property string|null $wmsdate
 * @property string|null $warehousedate
 * @property string|null $username
 * @property string|null $statusid
 * @property string|null $schemaid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Shipments\StorageScheduleInverseTraffic $traffic
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereInverseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereWarehousedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperation whereWmsdate($value)
 * @mixin \Eloquent
 */
class StorageScheduleInverseOperation extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverseoperation';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'startdate', 'finishdate',
        'wmsdate', 'warehousedate', 'statusid',
    ];

    protected $attributes = [
        'statusid' => 'PT',
        'schemaid' => 'LOV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageScheduleInverseOperation::whereIn('storagescheduleinverseoperation.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverseoperation.centerid', $centers);
    }

    public static function searchForOrder($orderid)
    {
        return StorageScheduleInverseOperation::controlAccessUser()
            ->whereOrderid($orderid)->first();
    }

    public function traffic()
    {
        return $this->belongsTo(StorageScheduleInverseTraffic::class, 'orderid', 'orderid');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseOperation $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
