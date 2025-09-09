<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentStage extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'shipmentstages';
    public $timestamps = false;

    protected $fillable = [
        'orderid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
    ];


}
