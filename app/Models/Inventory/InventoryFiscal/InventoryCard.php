<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryCard extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'inventory_cards';

    protected $fillable = [
        'name',
        'configurationflag',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'statusid',
        'inventory_area_id',
        'inventory_aisle_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function inventoryarea(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class, 'inventory_area_id', 'id');
    }

    public function inventoryaisle(): BelongsTo
    {
        return $this->belongsTo(InventoryAisle::class, 'inventory_aisle_id', 'id');
    }

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];
}
