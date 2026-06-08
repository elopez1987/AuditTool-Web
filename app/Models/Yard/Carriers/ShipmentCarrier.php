<?php

namespace App\Models\Yard\Carriers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 *
 * @property int $id
 * @property string $centerid
 * @property string $name
 * @property string|null $carrierid
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $rfc
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
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier carriers($carries)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereRfc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCarrier withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ShipmentCarrier extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'shipmentcarriers';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'carrierid',
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

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return ShipmentCarrier::whereIn('shipmentcarriers.centerid', $centers)
            ->whereIn('shipmentcarriers.warehouseid', $warehouses);
    }

    //    ------------------------ Relaciones entre modelos -------------------------------
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    //    ------------------------ Relaciones entre modelos -------------------------------

    //    ------------------------ Filtro de las consultas -------------------------------
    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('shipmentcarriers.name', 'like', "%{$name}%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != '') {
            $query->where('shipmentcarriers.carrierid', '=', $carrierid);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('shipmentcarriers.statusid', $statusid);
        }
    }

    public function scopeCarriers($query, $carries)
    {
        if (! empty($carries)) {
            $query->whereIn('shipmentcarriers.id', $carries);
        }
    }

    //    ------------------------ Filtro de las consultas -------------------------------
}
