<?php

namespace App\Models\Yard\ShipmentTruck;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
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
 * @property string $name
 * @property string|null $truckid
 * @property string|null $volume
 * @property string|null $configurationflag
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereTruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruck withoutTrashed()
 * @mixin \Eloquent
 */
class ShipmentTruck extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'shipmenttrucks';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'name',
        'truckid',
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('shipmenttrucks.name', 'like', "%{$name}%");
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentTruck::whereIn('centerid', $centers);
    }
}
