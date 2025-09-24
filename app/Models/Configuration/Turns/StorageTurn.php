<?php

namespace App\Models\Configuration\Turns;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 * @property int $id
 * @property string $name
 * @property string|null $turnid
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
 * @property-read SystemCenterDistribution|null $center
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTurn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorageTurn extends Model
{
    use LogsActivity;

    protected $table = 'storageturns';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'name',
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
        $centers = SecurityAccessWarehouse::Centers();
        return StorageTurn::whereIn('centerid', $centers);
    }

    public static function turnActiveToSelect()
    {
        return StorageTurn::whereStatusid('E')
            ->pluck('name', 'id')->toArray();
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }
}
