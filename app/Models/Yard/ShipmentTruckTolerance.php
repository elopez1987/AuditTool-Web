<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentTruckTolerance extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'shipmenttrucktolerance';

    protected $fillable = [
        'centerid','truckid','description',
        'value01','value02','value03','value04','value05','value06','value07',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
