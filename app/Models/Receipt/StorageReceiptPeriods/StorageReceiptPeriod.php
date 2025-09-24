<?php

namespace App\Models\Receipt\StorageReceiptPeriods;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $name
 * @property string $startdate
 * @property string $finishdate
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property int $userid
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemSchema $schema
 * @property-read SystemStatus $status
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPeriod withoutTrashed()
 * @mixin \Eloquent
 */
class StorageReceiptPeriod extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'storagereceiptperiods';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'startdate',
        'finishdate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'useid',
        'schemaid',
        'statusid',
    ];

    protected $casts = [

    ];

    protected $attributes = [
        'statusid' => 'EPR-01',
        'schemaid' => 'EPR',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageReceiptPeriod::whereIn('storagereceiptperiods.warehouseid', $warehouses)
            ->whereIn('storagereceiptperiods.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function schema(): BelongsTo
    {
        return $this->belongsTo(SystemSchema::class, 'schemaid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'useid', 'id');
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
            self::creating(function (StorageReceiptPeriod $model) {
                $model->userid = \Auth::id();
            });
        }
    }
}
