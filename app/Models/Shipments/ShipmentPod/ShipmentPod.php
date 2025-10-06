<?php

namespace App\Models\Shipments\ShipmentPod;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read OrderDeliverie $order
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod carrierIn($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod customerIn($customer)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPod whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentPod extends Model
{
    use LogsActivity;

    public $timestamps = true;

    protected $table = 'shipmentpods';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'value01', 'value02', 'value03', 'value04', 'value05',
        'statusid',
        'created_at',
    ];

    protected $attributes = [
        'statusid' => 'PED',
        'schemaid' => 'POD',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentPod::whereIn('shipmentpods.warehouseid', $warehouses)
            ->whereIn('shipmentpods.centerid', $centers);
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function scopeCarrierIn($query, $carrierid)
    {
        if (! empty($carrierid)) {
            $query->whereIn('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeCustomerIn($query, $customer)
    {
        if (! empty($customer)) {
            $query->whereIn('orderdeliveries.customerid', $customer);
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('orderdeliveries.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeStatus($query, $status)
    {
        if (! empty($status)) {
            $query->where('shipmentpods.statusid', '=', $status);
        }
    }
}
