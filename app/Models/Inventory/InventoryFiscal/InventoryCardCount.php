<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $table = 'inventory_card_counts';

    protected $fillable = [
        'inventory_head_id',
        'inventory_aisle_id',
        'inventory_card_id',
        'user_count01_id',
        'user_count02_id',
        'statusid_count01',
        'statusid_count02',
        'key_user_count_01',
        'key_user_count_02',
    ];

    protected $attributes = [
        'statusid_count01' => 'F',
        'statusid_count02' => 'F',
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

    public function inventorylocationscount(): InventoryCardCount|HasMany
    {
        return $this->hasMany(InventoryLocationCount::class, 'inventory_card_id', 'inventory_card_id');
    }

    public function statusid_count(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid_count01', 'statusid');
    }

    public function statusid_count02(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid_count02', 'statusid');
    }

    public function user_count01(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_count01_id', 'id');
    }

    public function user_count02(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_count02_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            //            self::creating(function (StorageZone $model) {});
            //            self::updating(function (StorageZone $model) {});
            self::deleting(function (InventoryCardCount $model) {
                $model->inventorylocationscount()->delete();
            });
            //			self::restoring(function (StorageZone $model) {});
        }
    }
}
