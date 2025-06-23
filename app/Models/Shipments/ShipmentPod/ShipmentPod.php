<?php

namespace App\Models\Shipments\ShipmentPod;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Database\Eloquent\Model;

class ShipmentPod extends Model
{
    public $timestamps = true;
    protected $table = 'shipmentpods';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'value01', 'value02', 'value03', 'value04', 'value05',
        'statusid',
    ];


    protected $attributes = [
        'statusid' => 'PED',
        'schemaid' => 'POD',
    ];

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
        if (!empty($carrierid)) {
            $query->whereIn('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeCustomerIn($query, $customer)
    {
        if (!empty($customer)) {
            $query->whereIn('orderdeliveries.customerid', $customer);
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != "") {
            $query->where('orderdeliveries.shipmentid', 'like', "%$shipmentid%");
        }
    }


    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            $query->where('shipmentpods.statusid','=', $status);
        }
    }



}
