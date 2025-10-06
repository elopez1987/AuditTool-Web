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
 * @property string $delivery
 * @property string $partnumberid
 * @property int $quantity
 * @property string|null $username
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $unitmeasureid
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereInverseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereUnitmeasureid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseOperationDetail whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageScheduleInverseOperationDetail extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverseoperationdetails';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid',
        'delivery', 'partnumberid', 'quantity', 'unitmeasureid', 'statusid', 'value01',
        'value02', 'value03',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'DEV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageScheduleInverseOperationDetail::whereIn('storagescheduleinverseoperationdetails.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverseoperationdetails.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseOperationDetail $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
