<?php

namespace App\Models\Shipments;

use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $delivery
 * @property string $partnumberid
 * @property int|null $razoncode
 * @property string|null $keyoperation
 * @property string|null $keyoperationdate
 * @property string|null $keyoperationuser
 * @property string $schemaid
 * @property string $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereKeyoperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereKeyoperationdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereKeyoperationuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereRazoncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageKeyIncidenceOperation whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageKeyIncidenceOperation extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagesincidencekeyoperation';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'keyoperation',
        'keyoperationdate', 'keyoperationuser', 'partnumberid', 'delivery', 'razoncode',
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

        return StorageKeyIncidenceOperation::whereIn('storagesincidencekeyoperation.warehouseid', $warehouses)
            ->whereIn('storagesincidencekeyoperation.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'keyoperationuser', 'username');
    }

    public static function getData($request)
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return DB::table('storagesincidencekeyoperation')
            ->select(
                'storagesincidencekeyoperation.orderid as id',
                'orderdeliveries.shipmentid',
                'ordercustomers.name',
                'ordercustomers.city',
                'shipmentcarriers.name as carrier',
                'orderdeliveries.downloaddate',
                'storagesincidencekeyoperation.keyoperation',
                'storagesincidencekeyoperation.keyoperationdate',
                'storagesincidencekeyoperation.keyoperationuser',
                'systemstatus.name as status'
            )
            ->leftJoin('orderdeliveries', function ($join) {
                $join->on('storagesincidencekeyoperation.orderid', '=', 'orderdeliveries.id')
                    ->on('storagesincidencekeyoperation.centerid', '=', 'orderdeliveries.centerid')
                    ->on('storagesincidencekeyoperation.warehouseid', '=', 'orderdeliveries.warehouseid');
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
                $join->on('storagesincidencekeyoperation.statusid', '=', 'systemstatus.statusid')
                    ->on('storagesincidencekeyoperation.schemaid', '=', 'systemstatus.schemaid');
            })
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereIn('orderdeliveries.warehouseid', $warehouses)
            ->whereNull('storagesincidencekeyoperation.keyoperationuser')
            ->where(function ($query) use ($request) {
                if ($request['shipmentid'] !== '') {
                    $query->where('orderdeliveries.shipmentid', $request['shipmentid']);
                }
            })
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
                'storagesincidencekeyoperation.id',
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
            ->leftJoin('storagesincidencekeyoperation', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagesincidencekeyoperation.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storagesincidencekeyoperation.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storagesincidencekeyoperation.partnumberid');
            })
            ->leftJoin('storagereasonoutput', 'storageincidencejourneydetails.auxiliary02', '=', 'storagereasonoutput.id')
            ->whereIn('storageincidencejourneydetails.razoncode', $concepts)
            ->whereIn('storageincidencejourneydetails.centerid', $centers)
            ->whereIn('storageincidencejourneydetails.warehouseid', $warehouses)
            ->where('storageincidencejourneydetails.orderid', '=', $id)
            ->get();
    }
}
