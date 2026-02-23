<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\Bridgestone\Verificacion\StorageShipmentCheckTotal;
use App\Models\Shipments\Bridgestone\Verificacion\Storageverificationpending;
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
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id')->withDefault(
            [
                'id' => null,
                'barcode' => '',
                'boxnumber' => '',
                'locationid' => null,
                'keydate' => null,
                'entrydate' => null,
            ],
        );
    }

    public function header()
    {
        return $this->belongsTo(ShipmentHeader::class, 'id', 'orderid')
            ->withDefault([
                'locationid' => null,
            ]);
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
        return $this->belongsTo(OrderMonitor::class, 'id', 'orderid')->withDefault(
            ['outputdate' => null],
        );
    }

    public function billings()
    {
        return $this->hasMany(StorageShipmentBilling::class, 'orderid', 'id');
    }

    public function folio_count(): BelongsTo
    {
        return $this->belongsTo(StorageShipmentCheckTotal::class, 'id', 'orderid');
    }

    public function verification_pending(): BelongsTo
    {
        return $this->belongsTo(Storageverificationpending::class, 'id', 'orderid');
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
        if (trim($warehouseid) != '') {
            $query->where('orderdeliveries.warehouseid', $warehouseid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != '') {
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
        if (trim($carrierid) != '') {
            $query->where('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('orderdeliveries.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeWorkday($query, $workday)
    {
        if (trim($workday) != '') {
            $query->where('orderdeliveries.workday', '=', $workday);
        }
    }

    public function scopeTurn($query, $turnid)
    {
        if (trim($turnid) != '') {
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
            });
        }
    }
}
