<?php

namespace App\Models\Inventory\FiscalInventory\Stock;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStock extends Model
{
    protected $table = 'inventorystock';
    public $timestamps = false;
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'inventoryid',
        'locationid',
        'partnumberid',
        'quantity',
        'lpnid',
        'unitmeasureid',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryStock::whereIn('inventorystock.centerid', $centers)
            ->whereIn('inventorystock.warehouseid', $warehouses);
    }

    public function scopePartNumber($query, $prtnum)
    {
        if (trim($prtnum) != "") {
            $query->where('inventorystock.partnumberid', '=', $prtnum);
        }
    }


}
