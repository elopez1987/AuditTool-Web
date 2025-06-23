<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class StorageScheduleInverseOperationDetail extends Model
{

    protected $table = 'storagescheduleinverseoperationdetails';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid',
        'delivery', 'partnumberid', 'quantity', 'unitmeasureid', 'statusid','value01',
        'value02','value03'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'schemaid' => 'DEV',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseOperationDetail $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageScheduleInverseOperationDetail::whereIn('storagescheduleinverseoperationdetails.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverseoperationdetails.centerid', $centers);
    }

}
