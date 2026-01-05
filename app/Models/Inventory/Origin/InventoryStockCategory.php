<?php

namespace App\Models\Inventory\Origin;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $stockcategoryid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereStockcategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryStockCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InventoryStockCategory extends Model
{
    use LogsActivity;

    protected $table = 'inventorystockcategories';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'stockcategoryid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return static::whereIn('inventorystockcategories.warehouseid', $warehouses)
            ->whereIn('inventorystockcategories.centerid', $centers);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('description', 'like', "%{$name}%");
        }
    }
}
