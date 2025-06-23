<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

class StorageShipmentBilling extends Model
{
    protected $table = 'storageshipmentbillings';
    public $timestamps = false;

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


    static function getBillingsForCreateNewIncidence($orderid)
    {
        return StorageShipmentBilling::where('orderid', $orderid)
            ->pluck('billingdocument', 'billingdocument')->toArray();
    }

    public function order()
    {
        return $this->hasOne(OrderDeliverie::class, 'id', 'orderid');
    }


}
