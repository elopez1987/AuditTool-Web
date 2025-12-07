<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $centerid
 * @property int $categoryincid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence forDescription($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereCategoryincid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidence whereStatusid($value)
 *
 * @mixin \Eloquent
 */
class StorageCategoryIncidence extends Model
{
    public $timestamps = false;

    protected $table = 'storagecategoryincidences';

    protected $primaryKey = 'categoryincid';

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

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return StorageCategoryIncidence::whereIn('storagecategoryincidences.centerid', $centers)
            ->whereIn('storagecategoryincidences.warehouseid', $warehouses);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function scopeForDescription($query, $description)
    {
        if (trim($description) != '') {
            $query->where('storagecategoryincidences.description', 'like', "%{$description}%");
        }
    }
}
