<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentContainerMoveBox extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'shipmentcontainermoveboxlog';

    protected $fillable = [
        'orderid', 'containerid', 'lpnid', 'partnumberid', 'quantity', 'createuser', 'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }
    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentContainerMoveBox $model) {
                $model->createuser = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
