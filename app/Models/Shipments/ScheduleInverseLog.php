<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $orderid
 * @property string $username
 * @property string|null $description
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseLog whereUsername($value)
 * @mixin \Eloquent
 */
class ScheduleInverseLog extends Model
{
    use LogsActivity;

    protected $table = 'scheduleinverselog';

    protected $fillable = [
        'orderid', 'username', 'description',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AG',
        'schemaid' => 'LIN',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function createNewLog($shipment, $description)
    {
        ScheduleInverseLog::create([
            'orderid' => $shipment->id,
            'username' => Auth::user()->username,
            'description' => $description,
        ]);
    }
}
