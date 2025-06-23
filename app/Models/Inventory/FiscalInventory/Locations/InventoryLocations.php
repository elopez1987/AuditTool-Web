<?php

namespace App\Models\Inventory\FiscalInventory\Locations;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLocations extends Model
{
    protected $table = 'inventorylocations';
    public $timestamps = false;
    protected $primaryKey = 'recordid';

    protected $fillable = [
        "centerid",
        "warehouseid",
        "inventoryid",
        "areaid",
        "aisleid",
        "cardid",
        "locationid",
        "statusid01",
        "statusid02",
        "statusid03",
        "type",
        "unitmeasureid",
    ];

    protected $attributes = [
        'type' => 'A',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryLocations::whereIn('inventorylocations.centerid', $centers)
            ->whereIn('inventorylocations.warehouseid', $warehouses);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function scopeCard($query, $cardid)
    {
        if (trim($cardid) != '') {
            $query->where('inventorylocations.cardid', $cardid);
        }
    }

    public function scopeLocation($query, $locationid)
    {
        if (trim($locationid) != '') {
            $query->where('inventorylocations.locationid', $locationid);
        }
    }

    public function scopeAisle($query, $aisleid)
    {
        if (trim($aisleid) != '') {
            $query->where('inventorylocations.aisleid', $aisleid);
        }
    }


}
