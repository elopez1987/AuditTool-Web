<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Yard\Customer\OrderCustomer;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderMonitor extends Model
{
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
