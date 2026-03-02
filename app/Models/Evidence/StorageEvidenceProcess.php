<?php

namespace App\Models\Evidence;

use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageReason;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageEvidenceProcess
 *
 * @property string $centerid
 * @property string $warehouseid
 * @property string $shipmentid
 * @property int $turnid
 * @property string $workday
 * @property string $appointment
 * @property int|null $customerid
 * @property string|null $econumber
 * @property int $carrierid
 * @property string|null $boxnumber
 * @property int|null $providerid
 * @property int|null $locationid
 * @property int $razonstatusid
 * @property string $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary05
 * @property string|null $auxiliary04
 * @property string|null $auxiliary03
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $recordid
 * @property int|null $stockcategoryid
 * @property-read ShipmentCarrier $carrier
 * @property-read OrderCustomer|null $customer
 * @property-read StorageLocation|null $location
 * @property-read InventoryStockCategory|null $origen
 * @property-read ReceiptProvider|null $provider
 * @property-read StorageReason $razon
 * @property-read StorageTurn|null $turn
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forCategory($categoryid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forCustomer($customerid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forRangeDate($from_date, $to_date)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forTurn($turnid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess forWorkDay($workday)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereProviderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereRazonstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereStockcategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidenceProcess whereWorkday($value)
 *
 * @mixin \Eloquent
 */
class StorageEvidenceProcess extends Model
{
    protected $table = 'storageevidenceoperations';

    protected $primaryKey = 'recordid';

    protected $keyType = 'integer';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'turnid',
        'workday',
        'appointment',
        'customerid',
        'econumber',
        'carrierid',
        'boxnumber',
        'providerid',
        'locationid',
        'razonstatusid',
        'createuser',
        'stockcategoryid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'schemaid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageEvidenceProcess $model) {
                $model->createuser = Auth::user()->username;
                $model->appointment = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageEvidenceProcess::whereIn('storageevidenceoperations.warehouseid', $warehouses)
            ->whereIn('storageevidenceoperations.centerid', $centers);
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function origen()
    {
        return $this->belongsTo(InventoryStockCategory::class, 'stockcategoryid', 'id');
    }

    public function razon()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('storageevidenceoperations.statusid', '=', $statusid);
        } else {
            $query->where('storageevidenceoperations.statusid', '=', 'E');
        }
    }

    public function scopeForWorkDay($query, $workday)
    {
        if (trim($workday) != '') {
            $query->where('storageevidenceoperations.workday', $workday);
        }
    }

    public function scopeForRangeDate($query, $from_date, $to_date)
    {
        if (trim($from_date) != '' && trim($to_date) != '') {
            $query->whereBetween('storageevidenceoperations.workday', [$from_date, $to_date]);
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storageevidenceoperations.shipmentid', $shipmentid);
        }
    }

    public function scopeForCustomer($query, $customerid)
    {
        if (trim($customerid) != '') {
            $query->where('storageevidenceoperations.customerid', $customerid);
        }
    }

    public function scopeForTurn($query, $turnid)
    {
        if (trim($turnid) != '') {
            $query->where('storageevidenceoperations.turnid', $turnid);
        }
    }

    public function scopeForCategory($query, $categoryid)
    {
        if (trim($categoryid) != '') {
            $query->where('storageevidenceoperations.stockcategoryid', $categoryid);
        }
    }
}
