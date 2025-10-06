<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $barcode
 * @property string $trucknumber
 * @property string|null $econumber
 * @property int $carrierid
 * @property string $operatorname
 * @property string|null $phone
 * @property string|null $drivernumber
 * @property string|null $brand
 * @property string|null $typeoflicense
 * @property string|null $validity
 * @property \Illuminate\Support\Carbon $entrydate
 * @property string|null $finishdate
 * @property string|null $type
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property int $createuser
 * @property int|null $updateuser
 * @property string $schemaid
 * @property string $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier|null $carrier
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck ecoNumber($econumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck filterForRangeDate($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck folio($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck truck($trucknumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck truckNumber($trucknumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereDrivernumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereEntrydate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereTypeoflicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereUpdateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereValidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptTruck whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptTruck extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'receipttrucks';

    protected $casts = [
        'entrydate' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'carrierid',
        'operatorname',
        'phone',
        'drivernumber',
        'entrydate',
        'finishdate',
        'trucknumber',
        'econumber',
        'type',
        'createuser',
        'updateuser',
        'statusid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'brand',
        'typeoflicense',
        'validity',
        'drivernumber',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
        'statusid' => 'OU',
        'type' => 'Truck',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ReceiptTruck::whereIn('receipttrucks.warehouseid', $warehouses)
            ->whereIn('receipttrucks.centerid', $centers);
    }

    //    Relaciones
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('receipttrucks.id', '=', $folionumber);
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) !== '') {
            $query->where('receipttrucks.trucknumber', 'like', "%{$trucknumber}%");
        }
    }

    public function scopeTruck($query, $trucknumber)
    {
        if (trim($trucknumber) !== '') {
            $query->where('receipttrucks.trucknumber', '=', $trucknumber);
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) !== '') {
            $query->where('receipttrucks.econumber', 'like', "%{$econumber}%");
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('receipttrucks.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('receipttrucks.operatorname', 'like', "%{$name}%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('receipttrucks.carrierid', '=', $carrierid);
        }
    }

    public function scopeFilterForRangeDate($query, $startDate, $endDate)
    {
        if ($startDate !== '' && $endDate !== '') {
            $query->whereBetween('receipttrucks.entrydate', [$startDate, $endDate]);
        } else {
            $query->whereBetween('receipttrucks.entrydate', [Carbon::now()->subDays(90), Carbon::now()]);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) !== '') {
            $query->where('receipttrucks.warehouseid', '=', $warehouseid);
        }
    }

    //    Filtros de busqueda
    public static function searchTrucksForCarrier($carrierid)
    {
        return ReceiptTruck::controlAccessUser()->where('receipttrucks.carrierid', $carrierid)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->get();
    }

    public static function searchTruckForTruckNumber($trucknumber)
    {
        return ReceiptTruck::controlAccessUser()
            ->where('receipttrucks.trucknumber', '=', $trucknumber)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->first();
    }

    public static function searchTruckForBarcode($barcode)
    {
        return ReceiptTruck::controlAccessUser()->whereBarcode($barcode)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->first();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptTruck $model) {
                $model->createuser = Auth::user()->id;
                $model->entrydate = Carbon::now();
            });
        }
    }
}
