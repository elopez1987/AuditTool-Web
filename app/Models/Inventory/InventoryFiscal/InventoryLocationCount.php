<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLocationCount extends Model
{
    protected $fillable = [
        'inventory_head_id',
        'inventory_aisle_id',
        'inventory_card_id',
        'inventory_location_id',
        'inventorypartnumber01_id',
        'quantity01',
        'inventorypartnumber02_id',
        'quantity02',
        'user_count01_id',
        'user_count02_id',
        'statusid_count01',
        'statusid_count02',
        'statusid_location',
    ];

    public function inventoryHead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class);
    }

    public function inventoryAisle(): BelongsTo
    {
        return $this->belongsTo(InventoryAisle::class);
    }

    public function inventoryCard(): BelongsTo
    {
        return $this->belongsTo(InventoryCard::class);
    }

    public function inventoryLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class);
    }

    public function inventorypartnumber01(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber01_id');
    }

    public function quantity01(): BelongsTo
    {
        return $this->belongsTo(\string::class, 'quantity01');
    }

    public function inventorypartnumber02(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber02_id');
    }

    public function quantity02(): BelongsTo
    {
        return $this->belongsTo(\string::class, 'quantity02');
    }

    public function userCount01(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_count01_id');
    }

    public function userCount02(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_count02_id');
    }
}
