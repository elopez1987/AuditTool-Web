<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
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

    public function inventoryassignslocations(): HasMany
    {
        return $this->hasMany(InventoryAssignCounterLocation::class, 'inventory_assign_counter_card_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {});
        self::deleting(function (InventoryAssignCounterCard $model) {
            $model->inventoryassignslocations()->delete();
        });
    }
}
