<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

class StorageScheduleInverseTraffic extends Model
{

    protected $table = 'storagescheduleinversetraffic';


    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'delivery', 'partnumberid', 'typereport',
        'requestzare', 'responsezare', 'requestne', 'responsene', 'requestnc', 'responsenc',
        'zare', 'ne', 'adjust', 'nc', 'username'
    ];

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }




}
