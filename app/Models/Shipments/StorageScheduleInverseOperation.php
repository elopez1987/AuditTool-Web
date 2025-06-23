<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class StorageScheduleInverseOperation extends Model
{
    protected $table = 'storagescheduleinverseoperation';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'startdate', 'finishdate',
        'wmsdate', 'warehousedate', 'statusid'
    ];

//    protected $hidden = [
//        'created_at',
//        'updated_at',
//        'schemaid'
//    ];

    protected $attributes = [
        'statusid' => 'PT',
        'schemaid' => 'LOV',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseOperation $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageScheduleInverseOperation::whereIn('storagescheduleinverseoperation.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverseoperation.centerid', $centers);
    }

    static function searchForOrder($orderid)
    {
        return StorageScheduleInverseOperation::controlAccessUser()
            ->whereOrderid($orderid)->first();
    }

    public function traffic()
    {
        return $this->belongsTo(StorageScheduleInverseTraffic::class, 'orderid', 'orderid');
    }

}
