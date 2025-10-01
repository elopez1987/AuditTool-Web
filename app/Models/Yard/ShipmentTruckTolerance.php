<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $truckid
 * @property string $description
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereTruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckTolerance whereValue05($value)
 * @mixin \Eloquent
 */
class ShipmentTruckTolerance extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'shipmenttrucktolerance';

    protected $fillable = [
        'centerid','truckid','description',
        'value01','value02','value03','value04','value05','value06','value07',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
