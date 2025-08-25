<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\General\StorageComment;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Database\Eloquent\Model;

class OrderMonitorSchedulle extends Model
{
    protected $table = 'viewmonitorscheduelle';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return OrderMonitorSchedulle::whereIn('centerid', $centers)->whereIn('warehouseid', $warehouses);
    }

    protected $casts = [
        'outputdate' => 'datetime:d/m/Y H:m',
    ];


    public function transport()
    {
        return $this->belongsTo(StorageShipmentTransport::class, 'id', 'orderid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid')
            ->select('systemwarehouses.warehouseid', 'systemwarehouses.name');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id')
            ->select(['storageturns.id', 'storageturns.name']);
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')
            ->select(['shipmentcarriers.id', 'shipmentcarriers.name', 'shipmentcarriers.carrierid']);
    }

    public function comments()
    {
        return $this->hasMany(StorageComment::class, 'modelid', 'id')
            ->where('modeltype', '=', 'orderdeliveries');
    }

    public function header()
    {
        return $this->belongsTo(ShipmentHeader::class, 'id', 'orderid');
    }

    public function monitor()
    {
        return $this->belongsTo(OrderMonitor::class, 'id', 'orderid');
    }

    public function operation()
    {
        return $this->belongsTo(ShipmentDataOperation::class, 'id', 'orderid');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id')
            ->select('ordercustomers.id', 'ordercustomers.name', 'ordercustomers.city');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid')
            ->select('systemstatus.statusid', 'systemstatus.name');
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != "") {
            $query->where('viewmonitorscheduelle.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeForStatus($query, $status)
    {
        if (trim($status) != ""){
            $query->where('viewmonitorscheduelle.statusid', '=', $status);
        }
    }


}
