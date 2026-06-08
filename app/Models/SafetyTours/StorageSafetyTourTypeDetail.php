<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property int $id
 * @property int $typeid
 * @property int $tagid
 * @property int $userid
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageTags $tag
 * @property-read StorageSafetyTourType $type
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereTagid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourTypeDetail withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageSafetyTourTypeDetail extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'storagesafetytourtypedetails';

    protected $fillable = [
        'centerid', 'typeid', 'tagid', 'userid', 'value01', 'value02', 'value03',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageSafetyTourTypeDetail::whereIn('storagesafetytourtypedetails.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(StorageSafetyTourType::class, 'typeid');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(StorageTags::class, 'tagid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSafetyTourTypeDetail $model) {
                $model->userid = Auth::id();
            });
        }
    }
}
