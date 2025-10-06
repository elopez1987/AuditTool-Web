<?php

namespace App\Models\Configuration\Folios;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $folionumber
 * @property string $schemaid
 * @property string $statusid
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio description($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemFolio whereStatusid($value)
 *
 * @mixin \Eloquent
 */
class SystemFolio extends Model
{
    use LogsActivity;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'systemfolio';

    protected $fillable = [
        'centerid',
        'folionumber',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'FOL',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return SystemFolio::whereIn('systemfolio.centerid', $centers);
    }

    public function scopeDescription($query, $description)
    {
        if (trim($description) !== '') {
            $query->where('systemstatus.name', 'like', "%{$description}%");
        }
    }
}
