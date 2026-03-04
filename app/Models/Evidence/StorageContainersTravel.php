<?php

namespace App\Models\Evidence;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageContainersTravel
 *
 * @property string $centerid
 * @property string $warehouseid
 * @property string $lpnid
 * @property int $partnumberid
 * @property float $quantity
 * @property string $scanuser
 * @property string|null $filebox
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $created_at
 * @property string $statusid
 * @property string|null $schemaid
 * @property string|null $week
 * @property string|null $typeshipment
 * @property string|null $pallet
 * @property string|null $location
 * @property int|null $customerid
 * @property-read InventoryPartnumber $sku
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel forLpn($lpnid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel forSku($partnumberid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereFilebox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel wherePallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereScanuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereTypeshipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainersTravel whereWeek($value)
 *
 * @mixin \Eloquent
 */
class StorageContainersTravel extends Model
{
    public $timestamps = false;

    protected $table = 'storagecontainerstravel';

    protected $primaryKey = 'lpnid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'centerid', 'warehouseid', 'lpnid', 'partnumberid', 'quantity', 'scanuser', 'created_at',
        'statusid', 'value01', 'value02', 'value03', 'value04', 'value05',
        'week',
        'typeshipment',
        'pallet',
        'location',
        'customerid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageContainersTravel $model) {
                $model->scanuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageContainersTravel::whereIn('storagecontainerstravel.warehouseid', $warehouses)
            ->whereIn('storagecontainerstravel.centerid', $centers);
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    public function scopeForLpn($query, $lpnid)
    {
        if (trim($lpnid) != '') {
            $query->where('storagecontainerstravel.lpnid', $lpnid);
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storagecontainerstravel.value01', $shipmentid);
        }
    }

    public function scopeForSku($query, $partnumberid)
    {
        if (trim($partnumberid) != '') {
            $sku = InventoryPartnumber::controlAccessUser()
                ->where('prtnum', $partnumberid)->first();
            $query->where('storagecontainerstravel.partnumberid', $sku->id);
        }

    }
}
