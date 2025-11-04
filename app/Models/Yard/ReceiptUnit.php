<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

// Auxiliary01 = NOmbre del cliente o proveedor
// Auxiliary02 = Número de folio del cual fue generado en el proceso de Reautilizar folio
// Auxiliary03 = Tipo de entrada en los modulos de registro (TR = Transporte, QR = Acceso por QR, ST = Registro Estandar)
// Auxiliary04 =

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $barcode
 * @property string|null $shipmentid
 * @property int $carrierid
 * @property int $truckid
 * @property string $operatorname
 * @property string|null $drivernumber
 * @property string|null $boxnumber
 * @property string|null $econumber
 * @property string|null $trucknumber
 * @property string $containerseal
 * @property int|null $quantity
 * @property string|null $type
 * @property string|null $phone
 * @property string $entrydate
 * @property string|null $deliverdate
 * @property string|null $ensnare
 * @property string|null $keydate
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property string|null $unravel
 * @property string|null $toreceivedate
 * @property string|null $autorizaoutputdate
 * @property string|null $outputdate
 * @property int $zoneid
 * @property int|null $locationid
 * @property int $unitstatusid
 * @property int $razonstatusid
 * @property int $unittypeid
 * @property string $truckcategoryid
 * @property int|null $foliotruck
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property int $createuser
 * @property int|null $updateuser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier $carrier
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Yard\StorageReason $event
 * @property-read \App\Models\Yard\StorageFolioClient|null $folioclient
 * @property-read \App\Models\Yard\ReceiptUnitKey|null $key
 * @property-read \App\Models\Yard\StorageLocation|null $location
 * @property-read \App\Models\Yard\StorageReason $reason
 * @property-read SystemStatus $status
 * @property-read \App\Models\Yard\ReceiptUnitTicket|null $tiket
 * @property-read \App\Models\Yard\ReceiptTruck|null $tracto
 * @property-read ShipmentTruck $truck
 * @property-read \App\Models\Yard\ShipmentTruckCategory $truckcategory
 * @property-read \App\Models\Yard\ReceiptUnitData|null $unitdata
 * @property-read \App\Models\Yard\StorageUnitStatus $unitstatus
 * @property-read \App\Models\Yard\StorageUnitType $unittype
 * @property-read User|null $user
 * @property-read User|null $userclose
 * @property-read SystemWarehouse $warehouse
 * @property-read StorageZone|null $zone
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit box($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit carrierArray($carrier)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit center($centerid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit code($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit ecoNumber($econumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit filterForRangeDate($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit folio($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit location($location)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit rason($rasonid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit razonCode($razon)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit shipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit truckNumber($trucknumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAutorizaoutputdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereContainerseal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereDeliverdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereDrivernumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereEnsnare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereEntrydate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereFoliotruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereKeydate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereOutputdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereRazonstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereToreceivedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereTruckcategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereTruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereUnitstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereUnittypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereUnravel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereUpdateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnit whereZoneid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptUnit extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'receiptunits';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'shipmentid',
        'carrierid',
        'truckid',
        'containerseal',
        'operatorname',
        'entrydate',
        'startdate',
        'finishdate',
        'autorizaoutputdate',
        'outputdate',
        'locationid',
        'unittypeid',
        'trucknumber',
        'boxnumber',
        'econumber',
        'type',
        'zoneid',
        'phone',
        'name',
        'razonstatusid',
        'unitstatusid',
        'drivernumber',
        'truckcategoryid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'createuser',
        'updateuser',
        'statusid',
        'keydate',
        'foliotruck',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
        'statusid' => 'ES',
    ];

    protected $casts = [
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ReceiptUnit::whereIn('receiptunits.warehouseid', $warehouses)
            ->whereIn('receiptunits.centerid', $centers);
    }

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
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')->withTrashed();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id')->withTrashed();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id')->withTrashed();
    }

    public function truck()
    {
        return $this->belongsTo(ShipmentTruck::class, 'truckid')->withTrashed();
    }

    public function unitstatus()
    {
        return $this->belongsTo(StorageUnitStatus::class, 'unitstatusid');
    }

    public function truckcategory()
    {
        return $this->belongsTo(ShipmentTruckCategory::class, 'truckcategoryid');
    }

    public function reason()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function folioclient()
    {
        return $this->belongsTo(StorageFolioClient::class, 'id', 'folionumber');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public function userclose()
    {
        return $this->belongsTo(User::class, 'updateuser', 'id')->withTrashed();
    }

    public function key()
    {
        return $this->belongsTo(ReceiptUnitKey::class, 'id', 'folionumber');
    }

    public function tiket()
    {
        return $this->belongsTo(ReceiptUnitTicket::class, 'id', 'folionumber');
    }

    public function unitdata()
    {
        return $this->belongsTo(ReceiptUnitData::class, 'id', 'folionumber');
    }

    public function unittype()
    {
        return $this->belongsTo(StorageUnitType::class, 'unittypeid', 'id');
    }

    public function tracto()
    {
        return $this->belongsTo(ReceiptTruck::class, 'foliotruck', 'id');
    }

    //    --Scopes para filtros de tabla
    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != '') {
            $query->where('receiptunits.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != '') {
            $query->where('receiptunits.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeLocation($query, $location)
    {
        if (trim($location) != '') {
            $query->where('sl.name', 'like', "%{$location}%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (is_array($carrierid) && ! empty($carrierid)) {
            $query->whereIn('receiptunits.carrierid', $carrierid);
        } elseif (trim($carrierid) != '') {
            $query->where('receiptunits.carrierid', '=', $carrierid);
        }
    }

    public function scopeCenter($query, $centerid)
    {
        if (trim($centerid) != '') {
            $query->where('receiptunits.centerid', '=', $centerid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if ($warehouseid != '') {
            $query->where('receiptunits.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeRason($query, $rasonid)
    {
        if (is_array($rasonid) && ! empty($rasonid)) {
            $query->whereIn('receiptunits.razonstatusid', $rasonid);
        } elseif (trim($rasonid) != '') {
            $query->where('receiptunits.razonstatusid', '=', $rasonid);
        }
    }

    public function scopeUnitType($query, $unittypeid)
    {
        if (is_array($unittypeid) && ! empty($unittypeid)) {
            $query->whereIn('receiptunits.unittypeid', $unittypeid);
        } elseif (trim($unittypeid) != '') {
            $query->where('receiptunits.unittypeid', '=', $unittypeid);
        }
    }

    public function scopeRazonCode($query, $razon)
    {
        if (! empty($razon)) {
            $query->whereIn('receiptunits.razonstatusid', $razon);
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('receiptunits.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) != '') {
            $query->where('receiptunits.econumber', 'like', "%{$econumber}%");
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) != '') {
            $query->where('receiptunits.trucknumber', 'like', "%{$trucknumber}%");
        }
    }

    public function scopeBox($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('receiptunits.boxnumber', '=', $boxnumber);
        }
    }

    public function scopeCode($query, $barcode)
    {
        if (trim($barcode) != '') {
            $query->where('receiptunits.barcode', '=', $barcode);
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('receiptunits.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('receiptunits.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeFilterForRangeDate($query, $startDate, $endDate)
    {
        if ($startDate !== '' && $endDate !== '') {
            if ($startDate === $endDate) {
                $dataIn = Carbon::parse($startDate)->startOfDay();
                $dateOut = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('receiptunits.entrydate', [$dataIn, $dateOut]);
            } else {
                $query->whereBetween('receiptunits.entrydate', [$startDate, $endDate]);
            }
        }
    }

    public function scopeStatus($query, $status)
    {
        if (! empty($status)) {
            $query->whereIn('receiptunits.statusid', $status);
        }
    }

    public function scopeCarrierArray($query, $carrier)
    {
        if (! empty($carrier)) {
            $query->whereIn('receiptunits.carrierid', $carrier);
        }
    }

    // ----------------------------------------------------- Filtros del modelo -----------------------
    public static function searchForBarcode($barcode)
    {
        return ReceiptUnit::controlAccessUser()->whereBarcode($barcode)->whereNotIn('statusid', ['TR'])->first();
    }

    public static function searchUnitForArrayNotStatus($barcode, $status)
    {
        return ReceiptUnit::controlAccessUser()->whereBarcode($barcode)->whereNotIn('statusid', $status)->first();
    }

    public static function searchForBarcodeTruck($barcodetruck)
    {
        return ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])->where('foliotruck', $barcodetruck)->first();
    }

    public static function searchForId($id)
    {
        return ReceiptUnit::controlAccessUser()->where('receiptunits.id', $id)->whereNotIn('statusid', ['TR'])->first();
    }

    public static function searchForBoxNumber($boxnumber)
    {
        return ReceiptUnit::controlAccessUser()->where('boxnumber', $boxnumber)->whereNotIn('statusid', ['TR'])->first();
    }

    public static function getCountUnit($centerid)
    {
        return ReceiptUnit::whereCenterid($centerid)->whereNotIn('statusid', ['TR'])->count();
    }

    public static function searchUnitForLocation($locationid)
    {
        return ReceiptUnit::controlAccessUser()->where('locationid', $locationid)->whereNotIn('statusid', ['TR'])->first();
    }

    // ------------------------------------------------------ Funciones auxiliares ---------------------------------------------
    public static function updateUnitForRefuse($folio)
    {
        $unit = ReceiptUnit::controlAccessUser()->where('id', '=', $folio)->whereNotIn('statusid', ['TR'])->first();
        $unit->autorizaoutputdate = now();
        $unit->statusid = 'OU';
        $unit->shipmentid = '';
        $unit->unitstatusid = '3';
        $unit->save();
        StorageUnitTrace::createTraceUnit($unit, '23');

        return $unit;
    }

    // ------------------------------------------------------ Funciones auxiliares ---------------------------------------------
    public static function moveUnitToLostForInventory($receiptunit, $location)
    {
        $receiptunit->zoneid = $location->value02;
        $receiptunit->locationid = $location->value03;
        $receiptunit->save();
    }

    public static function updateUnitForInventory($receiptunit, $request)
    {
        $receiptunit->zoneid = $request['zoneid'];
        $receiptunit->locationid = $request['locationid'];
        $receiptunit->unitstatusid = $request['unitstatusid'];
        $receiptunit->save();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptUnit $model) {
                $model->createuser = Auth::user()->id;
                $model->entrydate = Carbon::now();
            });
        }
    }
}
