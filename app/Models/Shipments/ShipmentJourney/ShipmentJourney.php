<?php

namespace App\Models\Shipments\ShipmentJourney;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
