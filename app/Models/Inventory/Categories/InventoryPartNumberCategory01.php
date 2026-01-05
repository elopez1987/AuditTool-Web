<?php

namespace App\Models\Inventory\Categories;

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
 * @property string $centerid
 * @property string $warehouseid
 * @property string $description
 * @property string|null $configurationflag
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
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartNumberCategory01 whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class InventoryPartNumberCategory01 extends Model
{
    use LogsActivity;

    protected $table = 'inventorypartnumbercategory01';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return static::whereIn('inventorypartnumbercategory01.warehouseid', $warehouses)
            ->whereIn('inventorypartnumbercategory01.centerid', $centers);
    }

    public static function category01ActiveToSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory01::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->pluck('description', 'id')->toArray();
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('description', 'like', "%{$name}%");
        }
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
