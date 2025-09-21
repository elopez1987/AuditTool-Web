<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

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
