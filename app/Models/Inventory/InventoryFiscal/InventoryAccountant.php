<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryAccountant extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'inventory_accountants';

    protected $fillable = [
        'inventory_head_id',
        'inventory_supervisor_id',
        'user_id',
    ];

    public function inventoryhead(): BelongsTo
    {
        return $this->belongsTo(InventoryHead::class, 'inventory_head_id', 'id');
    }

    public function inventorysupervisor(): BelongsTo
    {
        return $this->belongsTo(InventorySupervisor::class, 'inventory_supervisor_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
