<?php

namespace App\Models\Inventory\FiscalInventory\Locations;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class InventoryLocationScan03 extends Model
{
    protected $table = 'inventorylocationscan03';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'inventoryid',
        'cardid',
        'locationid',
        'partnumberid01',
        'quantity01',
        'partnumberid02',
        'quantity02',
        'type',
        'createuser'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (InventoryLocationScan03 $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryLocationScan03::whereIn('inventorylocationscan03.centerid', $centers)
            ->whereIn('inventorylocationscan03.warehouseid', $warehouses);
    }

}
