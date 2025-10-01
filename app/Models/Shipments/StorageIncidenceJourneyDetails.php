<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $folioid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $delivery
 * @property string $partnumberid
 * @property string|null $partnumberiderror
 * @property int $quantity
 * @property string $unitmeasure
 * @property string $razoncode
 * @property string|null $comment
 * @property string $downloaddate
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $statusiddetail
 * @property string $schemaid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StorageCategoryIncidenceDetail|null $category
 * @property-read InventoryPartnumber|null $partnumber
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereDownloaddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails wherePartnumberiderror($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereRazoncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereStatusiddetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereUnitmeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourneyDetails whereWarehouseid($value)
 * @mixin \Eloquent
 */
class StorageIncidenceJourneyDetails extends Model
{
    protected $table = 'storageincidencejourneydetails';
    protected $primaryKey = 'folioid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'delivery',
        'partnumberid',
        'quantity',
        'unitmeasure',
        'razoncode',
        'comment',
        'downloaddate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusiddetail',
        'partnumberiderror',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusiddetail' => 'SN',
        'schemaid' => 'EIJ',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageIncidenceJourneyDetails::whereIn('storageincidencejourneydetails.warehouseid', $warehouses)
            ->whereIn('storageincidencejourneydetails.centerid', $centers);
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusiddetail', 'statusid');
    }

    public function category()
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'razoncode', 'categoryincdetailid');
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'prtnum');
    }

    public static function getDetailOfIncidenceForOrder($orderid)
    {
        return StorageIncidenceJourneyDetails::where('orderid', $orderid)->get();
    }
}
