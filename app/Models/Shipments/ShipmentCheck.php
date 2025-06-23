<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ShipmentCheck extends Model
{
    protected $table = 'shipmentcheck';
    protected $primaryKey = 'recordid';
    public $timestamps = false;

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'quantityrequested',
        'quantitytrue',
        'startdate',
        'finishdate',
        'createuser',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'KLE',
        'statusid' => 'AP',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentCheck::whereIn('shipmentcheck.warehouseid', $warehouses)
            ->whereIn('shipmentcheck.centerid', $centers);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }


}
