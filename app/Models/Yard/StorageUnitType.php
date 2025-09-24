<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $unittypeid
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
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereUnittypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorageUnitType extends Model
{
    use LogsActivity;

    protected $table = 'storageunittypes';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'unittypeid',
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

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageUnitType::whereIn('storageunittypes.centerid', $centers);
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storageunittypes.description', 'like', "%{$name}%");
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('statusid', '=', $statusid);
        }
    }
}
