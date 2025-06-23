<?php

namespace App\Models\Inventory\UnitMeasure;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryUnitMeasure extends Model
{
    public $timestamps = false;
    protected $table = 'inventoryunitsmeasure';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'unitmeasureid',
        'description',
        'ediunitmeasurecode',
        'schemaid',
        'statusid'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryUnitMeasure::whereIn('inventoryunitsmeasure.centerid', $centers);
    }

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];


    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('inventoryunitsmeasure.description', 'like', "%$name%");
        }
    }


}
