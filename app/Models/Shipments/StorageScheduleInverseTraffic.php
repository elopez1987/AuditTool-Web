<?php

namespace App\Models\Shipments;

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
 * @property string|null $partnumberid
 * @property string|null $typereport
 * @property string|null $requestzare
 * @property string|null $responsezare
 * @property string|null $zare
 * @property string|null $requestne
 * @property string|null $responsene
 * @property string|null $ne
 * @property string|null $requestnc
 * @property string|null $responsenc
 * @property string|null $nc
 * @property string|null $adjust
 * @property string $username
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Shipments\OrderDeliverie $order
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereAdjust($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereInverseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereNc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereNe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereRequestnc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereRequestne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereRequestzare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereResponsenc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereResponsene($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereResponsezare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereTypereport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseTraffic whereZare($value)
 *
 * @mixin \Eloquent
 */
class StorageScheduleInverseTraffic extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinversetraffic';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'delivery', 'partnumberid', 'typereport',
        'requestzare', 'responsezare', 'requestne', 'responsene', 'requestnc', 'responsenc',
        'zare', 'ne', 'adjust', 'nc', 'username',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }
}
