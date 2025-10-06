<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string|null $centerid
 * @property int $id
 * @property string $description
 * @property string $type
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution|null $center
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReasonOutput whereType($value)
 *
 * @mixin \Eloquent
 */
class StorageReasonOutput extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagereasonoutput';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'description',
        'type',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageReasonOutput::whereIn('centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid')
            ->select(['centerid', 'name']);
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid')
            ->select(['statusid', 'name']);
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('description', 'like', "%{$name}%");
        }
    }

    public static function getReasonOutPutsForType($type)
    {
        return StorageReasonOutput::controlAccessUser()
            ->where('type', $type)
            ->pluck('description', 'id')->toArray();
    }
}
