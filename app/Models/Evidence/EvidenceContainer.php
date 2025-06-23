<?php

namespace App\Models\Evidence;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EvidenceContainer extends Model
{
    public $timestamps = false;
    protected $table = 'storageevidencecontainers';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'partnumberid',
        'quantity',
        'lpnid',
        'adddatecheck',
        'scanusercheck',
        'filecheck',
        'adddateload',
        'scanuserload',
        'fileload',
        'statusid',
        'value01',
        'value02',
        'value03',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'FS',
        'schemaid' => 'RSC',
    ];

    protected $casts = [
        'adddatecheck' => 'datetime:d/m/Y H:s',
        'adddateload' => 'datetime:d/m/Y H:s',
    ];


//    protected static function boot()
//    {
//        parent::boot();
//        if (!app()->runningInConsole()) {
//            self::creating(function (StorageEvidenceProcess $model) {
//                $model->createuser = Auth::user()->username;
//                $model->appointment = Carbon::now();
//            });
//        }
//    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return EvidenceContainer::whereIn('storageevidencecontainers.warehouseid', $warehouses)
            ->whereIn('storageevidencecontainers.centerid', $centers);
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function usercheck()
    {
        return $this->belongsTo(User::class, 'scanusercheck', 'username');
    }

    public function userload()
    {
        return $this->belongsTo(User::class, 'scanuserload', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }


//    Scopes
    public function scopeForWorkday($query, $workday)
    {
        if (trim($workday) != "") {
            $query->where('storageevidenceoperations.workday', '=', $workday);
        }
    }

    public function scopeForLpn($query, $lpnid)
    {
        if (trim($lpnid) != '') {
            $query->where('storageevidencecontainers.lpnid', $lpnid);
        }
    }


    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storageevidenceoperations.shipmentid', $shipmentid);
        }
    }


    public function scopeForSku($query, $partnumberid)
    {
        if (trim($partnumberid) != '') {
            $sku = InventoryPartnumber::controlAccessUser()
                ->where('prtnum', $partnumberid)->first();
            $query->where('storageevidencecontainers.partnumberid', $sku->id);
        }

    }

}
