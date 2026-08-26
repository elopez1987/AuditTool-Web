<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAssignCounterCard extends Model
{
    protected $table = 'inventory_assign_counter_cards';

    protected $fillable = [
        'inventory_head_id',
        'inventory_card_count_id',
        'user_id',
        'statusid',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class);
    }

    public function inventorycardcount(): BelongsTo
    {
        return $this->belongsTo(InventoryCardCount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
