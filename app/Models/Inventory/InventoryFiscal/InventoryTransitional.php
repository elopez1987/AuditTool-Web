<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryTransitional extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'inventory_transitionals';

    protected $fillable = [
        'inventory_head_id',
        'inventory_location_id',
        'inventorypartnumber_id',
        'quantity',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventorylocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'inventory_location_id', 'id');
    }

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id', 'id');
    }
}
