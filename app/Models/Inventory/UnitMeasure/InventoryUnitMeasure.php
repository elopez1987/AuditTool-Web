<?php

namespace App\Models\Inventory\UnitMeasure;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use LaravelIdea\Helper\App\Models\Inventory\UnitMeasure\_IH_InventoryUnitMeasure_QB;

class InventoryUnitMeasure extends Model
{
    public $timestamps = false;

    protected $table = 'inventoryunitsmeasure';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'unitmeasureid',
        'description',
        'ediunitmeasurecode',
        'schemaid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public static function controlAccessUser(): _IH_InventoryUnitMeasure_QB|Builder
    {

        $username = Auth::user()->username;

        return InventoryUnitMeasure::query()
            ->whereIn(
                'inventoryunitsmeasure.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            )
            ->whereIn(
                'inventoryunitsmeasure.warehouseid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('warehouseid'),
            );

    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function scopeName($query, $name): void
    {
        if (trim($name) !== '') {
            $query->where('inventoryunitsmeasure.description', 'like', "%{$name}%");
        }
    }
}
