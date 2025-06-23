<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentTruckTolerance extends Model
{
    public $timestamps = false;
    protected $table = 'shipmenttrucktolerance';
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable =[
      'centerid','truckid','description',
        'value01','value02','value03','value04','value05','value06','value07',
    ];


}
