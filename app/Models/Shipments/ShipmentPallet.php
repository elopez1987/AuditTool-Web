<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShipmentPallet extends Model
{
    protected $table = 'shipmentpallets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ["centerid", "warehouseid", "orderid",
        "prtnum", "quantity", "adddate", "update", "adduserid", "lastuserid",
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ShipmentPallet $model) {
                $model->adddate = Carbon::now();
                $model->adduserid = Auth::user()->id;
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentPallet::whereIn('shipmentpallets.warehouseid', $warehouses)
            ->whereIn('shipmentpallets.centerid', $centers);
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'prtnum', 'prtnum');
    }


}
