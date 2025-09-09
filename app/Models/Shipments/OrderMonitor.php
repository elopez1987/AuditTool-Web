<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderMonitor extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'ordermonitor';
    public $timestamps = false;

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


}
