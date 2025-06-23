<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentDataOperation extends Model
{
    protected $table = "shipmentdataoperation";
    protected $primaryKey = 'id';
    public $timestamps = false;

    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }


    protected $fillable = ["centerid", "warehouseid", "orderid", "supervisor", "checker",
        "forklift", "startshipment", "startchecker", "endchecker", "startload", "endload",
        "closematerial", "senddocument", "auxiliary01", "auxiliary02", "auxiliary03", "auxiliary04", "auxiliary05", 'comment01',
        'comment02', 'comment03', 'comment04', 'comment05', 'fullpallet', 'fullpickng',
        "createuserid", "lastuserid", 'forload'];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return ShipmentDataOperation::whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses);
    }

    public function pallets()
    {
        return $this->hasMany(ShipmentPallet::class, 'orderid', 'orderid');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lastuserid', 'id');
    }




}
