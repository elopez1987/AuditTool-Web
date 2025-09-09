<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageScheduleInverseOperation extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagescheduleinverseoperation';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid', 'startdate', 'finishdate',
        'wmsdate', 'warehousedate', 'statusid'
    ];


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
