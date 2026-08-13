<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStockSap extends Model
{
    public $timestamps = false;

    protected $table = 'inventory_stock_sap';

    protected $fillable = [
        'inventory_head_id',
        'inventorypartnumber_id',
        'quantity',
    ];

    public function inventoryHead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class);
    }

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id', 'id');
    }
}
