<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAssignCounterLocation extends Model
{
    protected $table = 'inventory_assign_counter_locations';

    protected $fillable = [
        'inventory_assign_counter_card_id',
        'inventory_location_count_id',
        'user_id',
        'statusid',
    ];

    public function inventoryassigncountercard(): BelongsTo
    {
        return $this->belongsTo(InventoryAssignCounterCard::class, 'inventory_assign_counter_card_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function inventorylocationcount(): BelongsTo
    {
        return $this->belongsTo(InventoryLocationCount::class, 'inventory_location_count_id', 'id');
    }
}
