<?php

namespace App\Models\Evidence;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class StorageContainersTravel extends Model
{

    public $timestamps = false;
    protected $table = 'storagecontainerstravel';
    protected $primaryKey = 'lpnid';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'centerid', 'warehouseid', 'lpnid', 'partnumberid', 'quantity', 'scanuser', 'created_at',
        'statusid', 'value01', 'value02', 'value03', 'value04', 'value05',
        'week',
        'typeshipment',
        'pallet',
        'location',
        'customerid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageContainersTravel $model) {
                $model->scanuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageContainersTravel::whereIn('storagecontainerstravel.warehouseid', $warehouses)
            ->whereIn('storagecontainerstravel.centerid', $centers);
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    public function scopeForLpn($query, $lpnid)
    {
        if (trim($lpnid) != '') {
            $query->where('storagecontainerstravel.lpnid', $lpnid);
        }
    }


    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storagecontainerstravel.value01', $shipmentid);
        }
    }


    public function scopeForSku($query, $partnumberid)
    {
        if (trim($partnumberid) != '') {
            $sku = InventoryPartnumber::controlAccessUser()
                ->where('prtnum', $partnumberid)->first();
            $query->where('storagecontainerstravel.partnumberid', $sku->id);
        }

    }


}
