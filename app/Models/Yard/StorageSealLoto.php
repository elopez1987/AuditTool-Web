<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $description
 * @property string|null $boxnumber
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property int $createuser
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto name($description)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSealLoto whereValue05($value)
 * @mixin \Eloquent
 */
class StorageSealLoto extends Model
{
    use LogsActivity;

    public $timestamps = true;
    protected $table = 'storagesealloto';

    protected $fillable = [
        'centerid', 'description', 'boxnumber', 'createuser',
        'value01', 'value02', 'value03', 'value04', 'value05', 'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSealLoto::whereIn('storagesealloto.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $description)
    {
        if (! empty($description)) {
            $query->where('storagesealloto.description', 'like', "%{$description}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSealLoto $model) {
                $model->createuser = \Auth::id();
            });
        }
    }
}
