<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptunitid
 * @property int $razonstatusid
 * @property string $assignedto
 * @property int|null $operatoryard
 * @property int $zoneid
 * @property int $locationid
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageLocation $location
 * @property-read User|null $operator
 * @property-read StorageReason $razon
 * @property-read ReceiptUnit $receiptunit
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 * @property-read StorageZone|null $zone
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask forStatusTask($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereAssignedto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereOperatoryard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereRazonstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereReceiptunitid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTask whereZoneid($value)
 *
 * @mixin \Eloquent
 */
class StorageTask extends Model
{
    use LogsActivity;

    protected $table = 'storagetasks';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'receiptunitid',
        'razonstatusid',
        'operatoryard',
        'assignedto',
        'zoneid',
        'locationid',
        'statusid',
        'created_by',
        'closed_by',
        'closed_at',
    ];

    protected $attributes = [
        'statusid' => 'PD',
        'schemaid' => 'SKS',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageTask::whereIn('storagetasks.warehouseid', $warehouses)
            ->whereIn('storagetasks.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageTask $model) {
                $model->created_by = Auth::id();
            });
        }
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operatoryard', 'id');
    }

    public function receiptunit()
    {
        return $this->belongsTo(ReceiptUnit::class, 'receiptunitid', 'id');
    }

    public function razon()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public static function searchTaskForUnit($folionumber)
    {
        return StorageTask::controlAccessUser()->whereReceiptunitid($folionumber)
            ->whereStatusid('PD')
            ->first();
    }

    public function scopeForStatusTask($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('storagetasks.statusid', $statusid);
        } else {
            $query->where('storagetasks.statusid', 'PD');
        }
    }
}
