<?php

namespace App\Models\Configuration\Labels;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $labelid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLabelDetail whereLabelid($value)
 *
 * @mixin \Eloquent
 */
class InventoryLabelDetail extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'inventorylabeldetails';

    protected $keyType = 'integer';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'labelid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function getDetailForLabel($label)
    {
        $centers = SecurityAccessWarehouse::Centers();

        return InventoryLabelDetail::join('inventorylabels', 'inventorylabeldetails.labelid', '=', 'inventorylabels.labelid')
            ->whereIn('inventorylabels.centerid', $centers)
            ->where('inventorylabels.auxiliary01', '=', $label)
            ->select('inventorylabeldetails.description')->get();
    }
}
