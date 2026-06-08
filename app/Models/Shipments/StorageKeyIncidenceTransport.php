<?php

namespace App\Models\Shipments;

use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $delivery
 * @property string $partnumberid
 * @property int|null $razoncode
 * @property string|null $keytransport
 * @property string|null $keytransportdate
 * @property string|null $keytransportuser
 * @property string $schemaid
 * @property string $statusid
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereKeytransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereKeytransportdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereKeytransportuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereRazoncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceTransport whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageKeyIncidenceTransport extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storageincidencekeytransport';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'keytransport',
        'keytransportdate', 'keytransportuser', 'partnumberid', 'delivery', 'razoncode',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'PI',
        'schemaid' => 'KEY',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageKeyIncidenceTransport::whereIn('storageincidencekeytransport.warehouseid', $warehouses)
            ->whereIn('storageincidencekeytransport.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'keytransportuser', 'username');
    }

    public static function getAllKeyTransports()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return DB::table('storageincidencekeytransport')
            ->select(
                'storageincidencekeytransport.orderid as id',
                'orderdeliveries.shipmentid',
                'ordercustomers.name',
                'ordercustomers.city',
                'shipmentcarriers.name as carrier',
                'orderdeliveries.downloaddate',
                'storageincidencekeytransport.keytransport',
                'storageincidencekeytransport.keytransportdate',
                'storageincidencekeytransport.keytransportuser',
                'systemstatus.name as status'
            )

            ->leftJoin('orderdeliveries', function ($join) {
                $join->on('storageincidencekeytransport.orderid', '=', 'orderdeliveries.id')
                    ->on('storageincidencekeytransport.centerid', '=', 'orderdeliveries.centerid')
                    ->on('storageincidencekeytransport.warehouseid', '=', 'orderdeliveries.warehouseid');
            })
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid');
            })
            ->leftJoin('shipmentcarriers', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
                    ->on('orderdeliveries.centerid', '=', 'shipmentcarriers.centerid');
            })
            ->leftJoin('systemstatus', function ($join) {
                $join->on('storageincidencekeytransport.statusid', '=', 'systemstatus.statusid')
                    ->on('storageincidencekeytransport.schemaid', '=', 'systemstatus.schemaid');
            })
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereIn('orderdeliveries.warehouseid', $warehouses)
            ->whereNull('storageincidencekeytransport.keytransportuser')
            ->distinct()
            ->get();
    }

    public static function getConcepts(): array
    {
        return StorageCategoryIncidenceDetail::controlAccessUser()
            ->where('auxiliary04', '=', 'F')
            ->where('categoryincid', '=', '6')
            ->pluck('categoryincdetailid')->toArray();
    }

    public static function getDetailIncidenceForKey($id)
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        $concepts = self::getConcepts();

        return DB::table('storageincidencejourneydetails')
            ->select(
                'storageincidencekeytransport.id',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure',
                'storagecategoryincidencedetails.description as motivo',
                'storagecategoryincidencedetails.auxiliary02 as code',
                'storageincidencejourneydetails.comment',
                'systemstatus.name as status',
                'storagereasonoutput.description as responsable'
            )
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin(
                'storagecategoryincidencedetails',
                'storageincidencejourneydetails.razoncode',
                '=',
                'storagecategoryincidencedetails.categoryincdetailid'
            )
            ->leftJoin('systemstatus', function ($join) {
                $join->on('storageincidencejourneydetails.statusiddetail', '=', 'systemstatus.statusid')
                    ->on('storageincidencejourneydetails.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('storageincidencekeytransport', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storageincidencekeytransport.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storageincidencekeytransport.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storageincidencekeytransport.partnumberid');
            })
            ->leftJoin('storagereasonoutput', 'storageincidencejourneydetails.auxiliary02', '=', 'storagereasonoutput.id')
            ->whereIn('storageincidencejourneydetails.razoncode', $concepts)
            ->whereIn('storageincidencejourneydetails.centerid', $centers)
            ->whereIn('storageincidencejourneydetails.warehouseid', $warehouses)
            ->where('storageincidencejourneydetails.orderid', '=', $id)
            ->get();
    }
}
