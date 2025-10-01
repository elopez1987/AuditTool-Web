<?php

namespace App\Models\Configuration\Labels;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property int $labelid
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereLabelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabel whereStatusid($value)
 * @mixin \Eloquent
 */
class InventoryLabel extends Model
{
    public $timestamps = false;
    protected $table = 'inventorylabels';
    protected $keyType = 'integer';
    protected $primaryKey = 'labelid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
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
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryLabel::whereIn('inventorylabels.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }
}
