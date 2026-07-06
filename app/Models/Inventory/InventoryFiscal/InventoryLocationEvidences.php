<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryLocationEvidences extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'inventory_location_evidences';

    protected $fillable = [
        'inventory_head_id',
        'inventory_location_count_id',
        'path',
        'type',
        'user_id',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventorylocationcount(): BelongsTo
    {
        return $this->belongsTo(InventoryLocationCount::class, 'inventory_location_count_id', 'id');
    }

    public function usercreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
