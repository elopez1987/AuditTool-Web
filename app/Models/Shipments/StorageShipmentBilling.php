<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $billingdocument
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read \App\Models\Shipments\OrderDeliverie|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereBillingdocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentBilling whereWarehouseid($value)
 * @mixin \Eloquent
 */
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
