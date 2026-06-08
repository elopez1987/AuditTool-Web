<?php

namespace App\Models\Configuration\Parameters;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $parameterid
 * @property int $id
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string|null $value06
 * @property string|null $value07
 * @property string|null $value08
 * @property string|null $value09
 * @property string|null $value10
 * @property string|null $value11
 * @property string|null $value12
 * @property string|null $value13
 * @property string|null $value14
 * @property string|null $value15
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereParameterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue11($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue13($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue14($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailParameter whereValue15($value)
 *
 * @mixin \Eloquent
 */
class SystemDetailParameter extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'systemdetailparameter';

    protected $fillable = [
        'parameterid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'value06',
        'value07',
        'value08',
        'value09',
        'value10',
        'value11',
        'value12',
        'value13',
        'value14',
        'value15',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return SystemDetailParameter::whereIn('systemdetailparameter.value02', $warehouses)
            ->whereIn('systemdetailparameter.value01', $centers);
    }

    public static function getDetailParameter($parameterid, $centerid)
    {
        return SystemDetailParameter::where('parameterid', '=', $parameterid)
            ->where('value01', '=', $centerid)
            ->select(['value01', 'value02', 'value03'])->first();
    }

    public static function getDateilParameterForCenterAndWarehouse($parameterid, $model)
    {
        return SystemDetailParameter::where(
            'parameterid',
            '=',
            $parameterid
        )
            ->where('value01', '=', $model->centerid)
            ->where('value02', '=', $model->warehouseid)
            ->pluck('value04')->toArray();
    }
}
