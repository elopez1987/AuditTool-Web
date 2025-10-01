<?php

namespace App\Models\Yard\StorageZone;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 * @property int $id
 * @property string $centerid
 * @property string|null $warehouseid
 * @property string $zoneid
 * @property string $name
 * @property array<array-key, mixed>|null $configurationflag
 * @property int|null $capacity
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone whereZoneid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageZone withoutTrashed()
 * @mixin \Eloquent
 */
class StorageZone extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'storagezones';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'centerid',
        'warehouseid',
        'zoneid',
        'name',
        'capacity',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
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
    protected $casts = [
        'configurationflag' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return StorageZone::whereIn('storagezones.centerid', $centers)
            ->whereIn('storagezones.warehouseid', $warehouses);
    }

    //    --Relaciones entre modelos
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    //    --Filtros para modelos
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storagezones.name', 'like', "%{$name}%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) !== '') {
            $query->where('storagezones.warehouseid', '=', $warehouseid);
        }
    }
}
