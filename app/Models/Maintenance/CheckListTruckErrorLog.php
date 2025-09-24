<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $serialnumber
 * @property string $scanuser
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog whereScanuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTruckErrorLog whereSerialnumber($value)
 * @mixin \Eloquent
 */
class CheckListTruckErrorLog extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'checklistlifttruckerrorlog';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'scanuser',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return CheckListTruckErrorLog::whereIn('checklistlifttruckerrorlog.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (CheckListTruckErrorLog $model) {
                $model->scanuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }
}
