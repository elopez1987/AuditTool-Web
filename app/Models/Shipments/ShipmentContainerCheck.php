<?php

namespace App\Models\Shipments;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentContainerCheck extends Model
{
    public $timestamps = false;
    protected $table = 'shipmentcontainercheck';
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'orderid','lpnid','containerid','lpnchild','createuser','created_at','orderid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ShipmentContainerCheck $model) {
                $model->createuser = \Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }




}
