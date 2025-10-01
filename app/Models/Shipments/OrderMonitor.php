<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string|null $warehouseid
 * @property int $orderid
 * @property string $workday
 * @property int $turnid
 * @property string $appointdate
 * @property string|null $startpickdate
 * @property string|null $startbulkdate
 * @property string|null $startcheckdate
 * @property string|null $arrivetransportdate
 * @property string|null $loadstartdate
 * @property string|null $startdateclosematerial
 * @property string|null $startdatedoctofac
 * @property string|null $startbillingdate
 * @property string|null $autorizeoutput
 * @property \Illuminate\Support\Carbon|null $outputdate
 * @property int|null $userid
 * @property string $schemaid
 * @property string $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereAppointdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereArrivetransportdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereAutorizeoutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereLoadstartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereOutputdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartbillingdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartbulkdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartcheckdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartdateclosematerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartdatedoctofac($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStartpickdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitor whereWorkday($value)
 * @mixin \Eloquent
 */
class OrderMonitor extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected $table = 'ordermonitor';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'workday',
        'turnid',
        'appointdate',
        'startpickdate',
        'startbulkdate',
        'arrivetransportdate',
        'loadstartdate',
        'startdatedoctofac',
        'startbillingdate',
        'autorizeoutput',
        'outputdate',
        'quantitypallet',
        'quantitybox',
        'userid',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'SOM',
        'statusid' => 'AO',
    ];

    protected $casts = [
        'outputdate' => 'datetime:d/m/Y H:i',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
