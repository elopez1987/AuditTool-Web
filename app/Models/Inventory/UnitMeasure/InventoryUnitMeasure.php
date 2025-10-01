<?php

namespace App\Models\Inventory\UnitMeasure;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $centerid
 * @property string $unitmeasureid
 * @property string $description
 * @property string|null $ediunitmeasurecode
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereEdiunitmeasurecode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryUnitMeasure whereUnitmeasureid($value)
 * @mixin \Eloquent
 */
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
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryUnitMeasure::whereIn('inventoryunitsmeasure.centerid', $centers);
    }

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
        if (trim($name) !== '') {
            $query->where('inventoryunitsmeasure.description', 'like', "%{$name}%");
        }
    }
}
