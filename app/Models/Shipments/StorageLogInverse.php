<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageLogInverse extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storageloginverse';

    protected $fillable = [
        'username', 'description', 'shipment',
        'value01', 'value02', 'value03',
        'value04', 'value05'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageLogInverse $model) {
                $model->username = Auth::user()->username;
            });
        }
    }


    static function createNewLog($description, $shipment)
    {
        StorageLogInverse::create([
            'description' => $description,
            'shipment' => $shipment,
        ]);
    }

}
