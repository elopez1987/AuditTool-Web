<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageZone\StorageZone;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 *
 * @property int $id
 * @property string $centerid
 * @property string|null $warehouseid
 * @property int $zoneid
 * @property string|null $locationid
 * @property string $name
 * @property string|null $barcode
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse|null $warehouse
 * @property-read StorageZone $zone
 *
 * @method static Builder<static>|StorageLocation name($name)
 * @method static Builder<static>|StorageLocation newModelQuery()
 * @method static Builder<static>|StorageLocation newQuery()
 * @method static Builder<static>|StorageLocation onlyTrashed()
 * @method static Builder<static>|StorageLocation query()
 * @method static Builder<static>|StorageLocation status($statusid)
 * @method static Builder<static>|StorageLocation warehouse($warehouseid)
 * @method static Builder<static>|StorageLocation whereAuxiliary01($value)
 * @method static Builder<static>|StorageLocation whereAuxiliary02($value)
 * @method static Builder<static>|StorageLocation whereAuxiliary03($value)
 * @method static Builder<static>|StorageLocation whereAuxiliary04($value)
 * @method static Builder<static>|StorageLocation whereAuxiliary05($value)
 * @method static Builder<static>|StorageLocation whereBarcode($value)
 * @method static Builder<static>|StorageLocation whereCenterid($value)
 * @method static Builder<static>|StorageLocation whereConfigurationflag($value)
 * @method static Builder<static>|StorageLocation whereCreatedAt($value)
 * @method static Builder<static>|StorageLocation whereDeletedAt($value)
 * @method static Builder<static>|StorageLocation whereId($value)
 * @method static Builder<static>|StorageLocation whereLocationid($value)
 * @method static Builder<static>|StorageLocation whereName($value)
 * @method static Builder<static>|StorageLocation whereSchemaid($value)
 * @method static Builder<static>|StorageLocation whereUpdatedAt($value)
 * @method static Builder<static>|StorageLocation whereWarehouseid($value)
 * @method static Builder<static>|StorageLocation whereZoneid($value)
 * @method static Builder<static>|StorageLocation withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|StorageLocation withoutTrashed()
 * @method static Builder<static>|StorageLocation zone($zoneid)
 *
 * @mixin Eloquent
 */
class StorageLocation extends Model
{
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'storagelocations';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'zoneid',
        'name',
        'locationid',
        'barcode',
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

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageLocation $model) {
                $model->auxiliary05 = Auth::user()->username;
                $model->barcode = rand(100000, 999999);
            });
            self::updating(function (StorageLocation $model) {
                $model->auxiliary05 = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return StorageLocation::whereIn('storagelocations.centerid', $centers)
            ->whereIn('storagelocations.warehouseid', $warehouses);
    }

    //    Relaciones
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

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id')
            ->withDefault(['name' => ' ']);
    }

    //    Filtros Scopes
    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('storagelocations.name', 'like', "%$name%");
        }
    }

    public function scopeForZone($query, $zoneid)
    {
        if ($zoneid != '') {
            $query->where('storagelocations.zoneid', $zoneid);
        }
    }

    public function scopeForStatus($query, $statusid)
    {
        if ($statusid != '') {
            $query->where('storagelocations.statusid', $statusid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if ($warehouseid != '') {
            $query->where('storagelocations.warehouseid', $warehouseid);
        }
    }

    public static function activeLocationForZone()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return StorageLocation::whereStatusid('E')
            ->whereIn('storagelocations.centerid', $centers)
            ->whereIn('storagelocations.warehouseid', $warehouses)
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->select(['name', 'id'])->get();
    }

    //    Funciones auxiliares para otros modulos
    public static function updateLocation($locationid, $statusid, $boxnumber)
    {
        StorageLocation::controlAccessUser()
            ->where('id', $locationid)
            ->update(['statusid' => $statusid, 'auxiliary01' => $boxnumber]);
    }
}
