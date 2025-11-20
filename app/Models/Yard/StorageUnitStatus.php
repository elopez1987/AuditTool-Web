<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string|null $unitstatusid
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
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereUnitstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitStatus withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageUnitStatus extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'storageunitstatus';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'unitstatusid',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storageunitstatus.name', 'like', "%{$name}%");
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('storageunitstatus.statusid', '=', $statusid);
        }
    }

    public function scopeRazon($query, $razonstatusid)
    {
        if (trim($razonstatusid) !== '') {
            $query->where('storageunitstatus.auxiliary01', '=', $razonstatusid);
        }
    }
}
