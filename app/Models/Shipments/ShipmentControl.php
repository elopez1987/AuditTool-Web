<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $deliveryid
 * @property int $customerid
 * @property int $partnumberid
 * @property int $category1id
 * @property int $category2id
 * @property string $unitmeasureid
 * @property int|null $quantityrequested
 * @property int|null $quantitytrue
 * @property int|null $quantityfail
 * @property string|null $missingconceptid
 * @property-read StorageCategoryIncidenceDetail|null $concept
 * @property-read \App\Models\Shipments\OrderDeliverie $order
 * @property-read InventoryPartnumber $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereCategory1id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereCategory2id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereDeliveryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereMissingconceptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereQuantityfail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereQuantityrequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereQuantitytrue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereUnitmeasureid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentControl whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentControl extends Model
{
    public $timestamps = false;

    protected $table = 'shipmentcontrol';

    protected $primaryKey = 'recordid';

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
