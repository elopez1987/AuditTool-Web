<?php

namespace App\Models\Inventory\FiscalInventory\Dron;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLocationForDron extends Model
{
    public $timestamps = false;
    protected $table = 'inventorylocationsdron';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        "centerid",
        "warehouseid",
        "inventoryid",
        "areaid",
        "aisleid",
        "cardid",
        "locationid",
        "type",
        "unitmeasureid",
        'statusid',
        'partnumberid',
        'quantity',
        'error'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
//        Estatus
//            F - Faltante
//            C - Contado

        'statusid' => 'F',
        'schemaid' => 'INV',
        'unitmeasureid' => 'PAL'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryLocationForDron::whereIn('inventorylocationsdron.centerid', $centers)
            ->whereIn('inventorylocationsdron.warehouseid', $warehouses);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'prtnum');
    }




}
