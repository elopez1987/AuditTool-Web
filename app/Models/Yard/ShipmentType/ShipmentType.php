<?php

namespace App\Models\Yard\ShipmentType;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 *
 * @property int $id
 * @property string $centerid
 * @property string $name
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ShipmentType extends Model
{
    use LogsActivity;

    protected $table = 'shipmenttypes';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
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

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return ShipmentType::whereIn('shipmenttypes.centerid', $centers)
            ->whereIn('shipmenttypes.warehouseid', $warehouses);
    }

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

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }
}
