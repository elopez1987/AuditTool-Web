<?php

namespace App\Models\Evidence;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageEvidenceStock
 *
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $shipmentid
 * @property int $partnumberid
 * @property float $quantity
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property-read StorageEvidenceProcess $shipment
 * @property-read InventoryPartnumber $sku
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceStock whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageEvidenceStock extends Model
{
    public $timestamps = false;

    protected $table = 'storageevidencestock';

    protected $fillable = [
        'centerid', 'warehouseid', 'shipmentid', 'partnumberid', 'quantity',
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
        return $this->belongsTo(StorageEvidenceProcess::class, 'shipmentid', 'recordid');
    }
}
