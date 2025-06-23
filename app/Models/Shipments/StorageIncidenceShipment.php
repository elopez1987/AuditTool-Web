<?php

namespace App\Models\Shipments;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StorageIncidenceShipment extends Model
{
//    TODO Validar si el modelo y la tabla son utilizados de lo contrario borrarlos al final de la refactorizacion
    protected $table = 'storageincidenceshipments';
    protected $primaryKey = 'id';

    protected $fillable = [
        "centerid",
        "warehouseid",
        "orderid",
        "partnumberid",
        "lpnid",
        "quantity",
        "responsible",
        "categoryincid",
        "categoryincdetailid",
        "responsiblecorr",
    ];

//    protected static function boot()
//    {
//        parent::boot();
//        if (!app()->runningInConsole()) {
//            self::creating(function (StorageIncidenceShipment $model) {
//                $model->createuser = \Auth::user()->username;
//            });
//        }
//    }
//
//    public static function controlAccessUser()
//    {
//        $centers = SecurityAccessWarehouse::Centers();
//        $warehouses = SecurityAccessWarehouse::Warehouses();
//        return StorageIncidenceShipment::whereIn('storageincidenceshipments.centerid', $centers)
//            ->whereIn('storageincidenceshipments.warehouseid', $warehouses);
//    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }
}
