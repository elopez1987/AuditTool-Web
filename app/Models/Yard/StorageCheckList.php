<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string|null $warehouseid
 * @property string $name
 * @property string $type
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $auxiliary06
 * @property string|null $auxiliary07
 * @property string|null $auxiliary08
 * @property string|null $auxiliary09
 * @property string|null $auxiliary10
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckList whereWarehouseid($value)
 * @mixin \Eloquent
 */
class StorageCheckList extends Model
{
    use Notifiable;
    use LogsActivity;

    protected $table = 'storagechecklists';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'type',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return StorageCheckList::whereIn('storagechecklists.centerid', $centers)
            ->whereIn('storagechecklists.warehouseid', $warehouses);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storagechecklists.name', 'like', "%{$name}%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) !== '') {
            $query->where('storagechecklists.warehouseid', '=', $warehouseid);
        }
    }
}
