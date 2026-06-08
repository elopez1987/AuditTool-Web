<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $containerseal01
 * @property string|null $containerseal02
 * @property string|null $containerseal03
 * @property int|null $assignuser
 * @property string|null $receivername
 * @property string $schemaorderid
 * @property string $statusorderid
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read OrderDeliverie $shipment
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereAssignuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereContainerseal01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereContainerseal02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereContainerseal03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereReceivername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereSchemaorderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereStatusorderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentSeal whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentSeal extends Model
{
    use LogsActivity;

    protected $table = 'shipmentseals';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'containerseal01',
        'containerseal02',
        'containerseal03',
        'assignuser',
        'receivername',
        'statusid',
        'schemaorderid',
        'statusorderid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'statusid' => 'PE',
        'schemaid' => 'SAL',
        'schemaorderid' => 'KLE',
        'statusorderid' => 'PA',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return ShipmentSeal::whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignuser', 'id');
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('shipmentid', '=', $shipmentid);
        }
    }
}
