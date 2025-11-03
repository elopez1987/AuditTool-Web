<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ShipmentType\ShipmentType;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereIn(string $string, array<string> $array)
 *
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $shipmentid
 * @property string $workday
 * @property int $turnid
 * @property int $carrierid
 * @property int $customerid
 * @property int $shipmenttypeid
 * @property string $loadingdate
 * @property string $downloaddate
 * @property int|null $folionumber
 * @property string|null $confirmation
 * @property string|null $evidencefile
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property int $createuser
 * @property int|null $updateuser
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipments\StorageShipmentBilling> $billings
 * @property-read int|null $billings_count
 * @property-read ShipmentCarrier $carrier
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Shipments\ShipmentCheck|null $check
 * @property-read OrderCustomer $customer
 * @property-read ReceiptUnit|null $folio
 * @property-read \App\Models\Shipments\ShipmentHeader|null $header
 * @property-read ShipmentJourney|null $journey
 * @property-read \App\Models\Shipments\OrderMonitor|null $monitor
 * @property-read \App\Models\Shipments\ShipmentDataOperation|null $operation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipments\ShipmentPallet> $pallets
 * @property-read int|null $pallets_count
 * @property-read \App\Models\Shipments\ShipmentSeal|null $seal
 * @property-read ShipmentType $shipmenttype
 * @property-read SystemStatus $status
 * @property-read StorageShipmentTransport|null $transport
 * @property-read StorageTurn $turns
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie carrierIn($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie customerIn($customer)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie forCarrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie turn($turnid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereDownloaddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereEvidencefile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereLoadingdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereShipmenttypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereUpdateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie whereWorkday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDeliverie workday($workday)
 *
 * @mixin \Eloquent
 */
class OrderDeliverie extends Model
{
    use LogsActivity;

    protected $table = 'orderdeliveries';

    protected $fillable = ['centerid', 'warehouseid', 'shipmentid', 'workday',
        'turnid', 'carrierid', 'customerid', 'shipmenttypeid', 'folionumber',
        'loadingdate', 'downloaddate', 'auxiliary01', 'auxiliary02', 'auxiliary03',
        'auxiliary04', 'auxiliary05', 'evidencefile', 'confirmation', 'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AP',
        'schemaid' => 'KLE',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return OrderDeliverie::whereIn('orderdeliveries.warehouseid', $warehouses)
            ->whereIn('orderdeliveries.centerid', $centers);
    }

    //   --------------------------  Relaciones -------------------------------------------
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function turns(): BelongsTo
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function shipmenttype()
    {
        return $this->belongsTo(ShipmentType::class, 'shipmenttypeid');
    }

    public function folio()
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id');
    }

    public function header()
    {
        return $this->belongsTo(ShipmentHeader::class, 'id', 'orderid');
    }

    public function check()
    {
        return $this->belongsTo(ShipmentCheck::class, 'id', 'orderid');
    }

    public function operation()
    {
        return $this->belongsTo(ShipmentDataOperation::class, 'id', 'orderid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    public function journey()
    {
        return $this->belongsTo(ShipmentJourney::class, 'id', 'orderid');
    }

    public function transport()
    {
        return $this->belongsTo(StorageShipmentTransport::class, 'id', 'orderid');
    }

    public function seal()
    {
        return $this->belongsTo(ShipmentSeal::class, 'id', 'orderid');
    }

    public function pallets()
    {
        return $this->hasMany(ShipmentPallet::class, 'orderid', 'id');
    }

    public function monitor()
    {
        return $this->belongsTo(OrderMonitor::class, 'id', 'orderid');
    }

    public function billings()
    {
        return $this->hasMany(StorageShipmentBilling::class, 'orderid', 'id');
    }

    //  -----------------------------  Scopes para filtros  ------------------------------------------
    public function scopeForStatus($query, $statusid)
    {
        if (is_array($statusid) && ! empty($statusid)) {
            $query->whereIn('orderdeliveries.statusid', $statusid);
        } elseif (trim($statusid) != '') {
            $query->where('orderdeliveries.statusid', '=', $statusid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) !== '') {
            $query->where('orderdeliveries.warehouseid', $warehouseid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeCarrierIn($query, $carrierid)
    {
        if (! empty($carrierid)) {
            $query->whereIn('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeCustomerIn($query, $customer)
    {
        if (! empty($customer)) {
            $query->whereIn('orderdeliveries.customerid', $customer);
        }
    }

    public function scopeForCarrier($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('orderdeliveries.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeWorkday($query, $workday)
    {
        if (trim($workday) !== '') {
            $query->where('orderdeliveries.workday', '=', $workday);
        }
    }

    public function scopeTurn($query, $turnid)
    {
        if (trim($turnid) !== '') {
            $query->where('orderdeliveries.turnid', '=', $turnid);
        }
    }

    //  -------------------------  Consultas  ---------------------------------------------
    public static function validateShipmentOrder($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->whereShipmentid($shipmentid)
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
    }

    public static function getOrderForStatus()
    {
        return OrderDeliverie::controlAccessUser()->select(['statusid', DB::raw('COUNT(statusid) as total_for_status')])->groupBy('statusid')->get();
    }

    public static function searchForShipment($shipment)
    {
        return OrderDeliverie::controlAccessUser()->whereShipmentid($shipment)->first();
    }

    public static function searchShipmentForArrayStatus($folionumber, $status)
    {
        return OrderDeliverie::controlAccessUser()->where('orderdeliveries.folionumber', $folionumber)
            ->whereNotIn('orderdeliveries.statusid', $status)->first();
    }

    public static function searchForUnit($folio)
    {
        return OrderDeliverie::controlAccessUser()->where('orderdeliveries.folionumber', $folio)->first();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (OrderDeliverie $model) {
                $model->createuser = Auth::user()->id;
                $model->auxiliary05 = bin2hex(random_bytes(30));
            });
        }
    }
}
