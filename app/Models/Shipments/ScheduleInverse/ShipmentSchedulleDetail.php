<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $id
 * @property int $incidencejourneyid
 * @property int $shipmentschedulleid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail whereIncidencejourneyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail whereShipmentschedulleid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSchedulleDetail whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentSchedulleDetail extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storageshipmentsscheduledetail';

    protected $fillable = ['centerid', 'warehouseid', 'incidencejourneyid', 'shipmentschedulleid'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentSchedulleDetail::whereIn('storageshipmentsscheduledetail.warehouseid', $warehouses)
            ->whereIn('storageshipmentsscheduledetail.centerid', $centers);
    }
}
