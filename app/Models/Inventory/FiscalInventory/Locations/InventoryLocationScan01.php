<?php

namespace App\Models\Inventory\FiscalInventory\Locations;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLocationScan01 extends Model
{
    protected $table = 'inventorylocationscan01';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'inventoryid',
        'cardid',
        'locationid',
        'partnumberid',
        'quantity',
        'quantitylpn',
        'quantityover',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::updating(function (InventoryLocationScan01 $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryLocationScan01::whereIn('inventorylocationscan01.centerid', $centers)
            ->whereIn('inventorylocationscan01.warehouseid', $warehouses);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }


}
