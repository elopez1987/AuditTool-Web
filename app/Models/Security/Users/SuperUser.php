<?php

namespace App\Models\Security\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $userid
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperUser whereUserid($value)
 *
 * @mixin \Eloquent
 */
class SuperUser extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'securitysuperusers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'userid',
        'created_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (SuperUser $model) {
                $model->created_at = Carbon::now();
            });
        }
    }
}
