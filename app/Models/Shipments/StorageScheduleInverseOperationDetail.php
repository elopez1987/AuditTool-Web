<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageScheduleInverseOperationDetail extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverseoperationdetails';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'inverseid',
        'delivery', 'partnumberid', 'quantity', 'unitmeasureid', 'statusid','value01',
        'value02','value03',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'DEV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageScheduleInverseOperationDetail::whereIn('storagescheduleinverseoperationdetails.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverseoperationdetails.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseOperationDetail $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
