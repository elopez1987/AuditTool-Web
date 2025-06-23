<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentContainer extends Model
{
    use SoftDeletes;
    protected $table = 'shipmentcontainers';
    public $timestamps = false;


    protected $fillable = ['centerid', 'warehouseid', 'orderid', 'deliveryid',
        'customerid', 'lpnid', 'scanuser', 'scandate', 'scanuserload',
        'scandateload', 'statusid', 'closedate'];

    protected $hidden = [
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'FS',
        'schemaid' => 'RSC',
    ];

    protected $casts = [
        'scandate' => 'datetime:d/m/Y H:s',
        'scandateload' => 'datetime:d/m/Y H:s',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ShipmentContainer $model) {
                $model->scanuser = \Auth::user()->username;
                $model->scandate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentContainer::whereIn('shipmentcontainers.warehouseid', $warehouses)
            ->whereIn('shipmentcontainers.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function userload()
    {
        return $this->belongsTo(User::class, 'scanuserload', 'username');
    }






}
