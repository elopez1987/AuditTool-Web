<?php

namespace App\Models\Inventory\PartNumber;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryPartNumberData01 extends Model
{
    protected $table = 'inventorypartnumberdata01';

    protected $fillable = [
        'inventorypartnumber_id',
        'container_type',
        'pn_turbo',
        'quantity',
        'machines_component',
    ];

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id');
    }
}
