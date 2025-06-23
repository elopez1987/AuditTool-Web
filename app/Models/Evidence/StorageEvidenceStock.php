<?php

namespace App\Models\Evidence;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageEvidenceStock extends Model
{
    public $timestamps = false;
    protected $table = 'storageevidencestock';

    protected $fillable = [
        'centerid', 'warehouseid', 'shipmentid', 'partnumberid', 'quantity'
    ];


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageEvidenceStock::whereIn('storageevidencestock.warehouseid', $warehouses)
            ->whereIn('storageevidencestock.centerid', $centers);
    }


    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function shipment()
    {
        return $this->belongsTo(StorageEvidenceProcess::class,'shipmentid','recordid');
    }

}
