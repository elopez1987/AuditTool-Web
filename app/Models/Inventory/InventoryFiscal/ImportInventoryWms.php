<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportInventoryWms extends Model
{
    public $timestamps = false;

    protected $table = 'inventory_stock_wms';

    protected $fillable = [
        'inventory_head_id',
        'inventory_location_id',
        'inventorypartnumber_id',
        'quantity_location',
        'quantity_lpns',
    ];

    public function inventoryHead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class);
    }

    public function inventoryLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class);
    }

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id');
    }
}
