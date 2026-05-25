<?php

namespace App\Models\Inventory\InventoryFiscal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryAisleCount extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'inventory_aisle_counts';

    public $timestamps = false;

    protected $fillable = [
        'inventory_head_id',
        'inventory_aisle_id',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventoryaisle(): BelongsTo
    {
        return $this->belongsTo(InventoryAisle::class, 'inventory_aisle_id', 'id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(InventoryCardCount::class, 'inventory_aisle_id', 'inventory_aisle_id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            //            self::creating(function (StorageZone $model) {});
            //            self::updating(function (StorageZone $model) {});
            self::deleting(function (InventoryAisleCount $model) {
                $model->cards()->delete();
            });
            //			self::restoring(function (StorageZone $model) {});
        }
    }
}
