<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryLocation extends Model
{
    use LogsActivity;
    use SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'inventory_locations';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'configurationflag',
        'inventoryunitsmeasure_id',
        'inventory_card_id',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'statusid',
    ];

    public function inventoryunitsmeasure(): BelongsTo
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'inventoryunitsmeasure_id');
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryLocation::whereIn('inventory_locations.centerid', $centers)
            ->whereIn('inventory_locations.warehouseid', $warehouses);
    }
}
