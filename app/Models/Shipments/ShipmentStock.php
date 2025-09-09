<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class ShipmentStock extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    
    protected $table = 'shipmentstock';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'deliveryid', 'partnumberid',
        'quantity', 'lpnid', 'createuser'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ShipmentStock $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'createuser','username');
    }



}
