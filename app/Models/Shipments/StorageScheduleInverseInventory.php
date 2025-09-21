<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageScheduleInverseInventory extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverseinventory';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'delivery', 'inverseid', 'username',
        'requestadjust', 'replyadjust', 'note', 'value01', 'value02',
        'value03', 'value04', 'value05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AD',
        'schemaid' => 'LIV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseInventory $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
