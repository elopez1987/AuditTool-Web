<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageScheduleInverseTraffic extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinversetraffic';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'delivery', 'partnumberid', 'typereport',
        'requestzare', 'responsezare', 'requestne', 'responsene', 'requestnc', 'responsenc',
        'zare', 'ne', 'adjust', 'nc', 'username',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }
}
