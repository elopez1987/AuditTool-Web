<?php

namespace App\Models\Inventory\InventoryFiscal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryCardCount extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $fillable = [
        'inventory_head_id',
        'inventory_aisle_id',
        'inventory_card_id',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventoryaisle(): BelongsTo
    {
        return $this->belongsTo(InventoryAisle::class, 'inventory_aisle_id', 'id');
    }

    public function inventorycard(): BelongsTo
    {
        return $this->belongsTo(InventoryCard::class, 'inventory_card_id', 'id');
    }
}
