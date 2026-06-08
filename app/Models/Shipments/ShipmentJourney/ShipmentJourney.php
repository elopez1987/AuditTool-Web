<?php

namespace App\Models\Shipments\ShipmentJourney;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $location
 * @property string|null $eta
 * @property string|null $arrivedate
 * @property int|null $razonoutputid
 * @property string|null $receiptenddate
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property int|null $createuserid
 * @property int|null $updateuserid
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read OrderDeliverie $order
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney forCarrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney forCustomer($customername)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereArrivedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereCreateuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereEta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereRazonoutputid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereReceiptenddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereUpdateuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentJourney whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentJourney extends Model
{
    use LogsActivity;

    protected $table = 'shipmentjourneys';

    protected $fillable = ['centerid', 'warehouseid', 'orderid', 'location',
        'eta', 'arrivedate', 'razonoutputid', 'receiptenddate', 'auxiliary01',
        'auxiliary02', 'auxiliary03', 'auxiliary04', 'auxiliary05',
        'createuserid', 'updateuserid', 'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'SHJ',
        'statusid' => 'IT',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentJourney::whereIn('shipmentjourneys.warehouseid', $warehouses)
            ->whereIn('shipmentjourneys.centerid', $centers);
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('orderdeliveries.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeForCarrier($query, $carrierid)
    {
        if (! empty(trim($carrierid))) {
            $query->where('orderdeliveries.carrierid', '=', $carrierid);
        }
    }

    public function scopeForCustomer($query, $customername)
    {
        if (! empty(trim($customername))) {
            $query->where('ordercustomers.name', 'like', "%{$customername}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentJourney $model) {
                $model->createuserid = Auth::id();
            });
        }
    }
}
