<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $folioid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $shipmentid
 * @property string|null $customername
 * @property string|null $carriername
 * @property int $carrierid
 * @property string|null $statusdevid
 * @property float|null $quantity
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle carrierIn($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereCarriername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereCustomername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereStatusdevid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentForSchedulle whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentForSchedulle extends Model
{
    public $timestamps = false;

    protected $table = 'vw_shipmentforschedulleinverse';

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentForSchedulle::whereIn('vw_shipmentforschedulleinverse.warehouseid', $warehouses)
            ->whereIn('vw_shipmentforschedulleinverse.centerid', $centers);
    }

    public function scopeCarrierIn($query, $carrierid)
    {
        if (! empty($carrierid)) {
            $query->whereIn('vw_shipmentforschedulleinverse.carrierid', $carrierid);
        }
    }
}
