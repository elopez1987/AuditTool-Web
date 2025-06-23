<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ShipmentControl extends Model
{
    protected $table = 'shipmentcontrol';
    protected $primaryKey = 'recordid';
    public $timestamps = false;

    protected $fillable = ['centerid', 'warehouseid', 'orderid', 'deliveryid',
        'customerid', 'partnumberid', 'category1id', 'category2id', 'unitmeasureid',
        'quantityrequested', 'quantitytrue', 'quantityfail', 'missingconceptid',
    ];

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function product()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function concept()
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'missingconceptid', 'categoryincdetailid');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentControl::whereIn('shipmentcontrol.warehouseid', $warehouses)
            ->whereIn('shipmentcontrol.centerid', $centers);
    }








}
