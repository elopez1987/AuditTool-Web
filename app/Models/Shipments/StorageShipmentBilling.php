<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

class StorageShipmentBilling extends Model
{
    public $timestamps = false;
    protected $table = 'storageshipmentbillings';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'billingdocument',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public static function getBillingsForCreateNewIncidence($orderid)
    {
        return StorageShipmentBilling::where('orderid', $orderid)
            ->pluck('billingdocument', 'billingdocument')->toArray();
    }

    public function order()
    {
        return $this->hasOne(OrderDeliverie::class, 'id', 'orderid');
    }
}
