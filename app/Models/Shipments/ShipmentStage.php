<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $orderid
 * @property int $value01
 * @property int|null $value02
 * @property int|null $value03
 * @property int|null $value04
 * @property string|null $value05
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStage whereValue05($value)
 *
 * @mixin \Eloquent
 */
class ShipmentStage extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'shipmentstages';

    protected $fillable = [
        'orderid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
