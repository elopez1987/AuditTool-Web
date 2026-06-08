<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $folioid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property int $carrierid
 * @property int $createuser
 * @property string|null $statuskeyid
 * @property string|null $schemakeyid
 * @property string|null $statuskeytrid
 * @property string|null $schemakeytrid
 * @property string|null $statusdevid
 * @property string|null $schemadevid
 * @property string|null $responsedate
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read StorageKeyIncidenceOperation $key
 * @property-read StorageKeyIncidenceTransport $keytransport
 * @property-read OrderDeliverie $order
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney forDate($datein, $dateout)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney statusIncidence($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereResponsedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereSchemadevid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereSchemakeyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereSchemakeytrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereStatusdevid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereStatuskeyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereStatuskeytrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageIncidenceJourney whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageIncidenceJourney extends Model
{
    protected $table = 'storageincidencejourneys';

    protected $primaryKey = 'folioid';

    protected $fillable = ['centerid', 'warehouseid', 'orderid',
        'createuser', 'carrierid', 'schemaid', 'statusid',
        'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04',
        'auxiliary05', 'statuskeyid', 'statusid', 'statuskeytrid', 'statusdevid',
        'responsedate',

    ];

    protected $hidden = [
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [

        //        Estatus del reporte
        'statusid' => 'AB',
        'schemaid' => 'EIJ',

        //        Estatus de firma de almacen
        'statuskeyid' => 'SF',
        'schemakeyid' => 'KEY',

        //        Estatus de firma de transporte
        'statuskeytrid' => 'SF',
        'schemakeytrid' => 'KEY',

        //        Estatus de cita de entrega
        'statusdevid' => 'SIC',
        'schemadevid' => 'DEC',

    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageIncidenceJourney $model) {
                $model->createuser = Auth::id();
            });
            self::updating(function (StorageIncidenceJourney $model) {});
            self::deleting(function (StorageIncidenceJourney $model) {
                $model->details()->delete();
            });

        }
    }

    protected $casts = [
        //        'created_at' => 'datetime:Y-m-d H:s',
    ];

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function key()
    {
        return $this->belongsTo(StorageKeyIncidenceOperation::class, 'orderid', 'orderid');
    }

    public function keytransport()
    {
        return $this->belongsTo(StorageKeyIncidenceTransport::class, 'orderid', 'orderid');
    }

    public function details(): StorageIncidenceJourney|HasMany
    {
        return $this->hasMany(StorageIncidenceJourneyDetails::class, 'orderid', 'orderid');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageIncidenceJourney::whereIn('storageincidencejourneys.warehouseid', $warehouses)
            ->whereIn('storageincidencejourneys.centerid', $centers);
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

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('orderdeliveries.shipmentid', $shipmentid);
        }
    }

    public function scopeForDate($query, $datein, $dateout)
    {
        if (! empty($datein) && ! empty($dateout)) {
            $query->whereBetween('orderdeliveries.downloaddate', [$datein, $dateout]);
        } else {
            $query->whereDate('orderdeliveries.downloaddate', '>=', Carbon::now()->subDays(10));
        }
    }

    public function scopeStatusIncidence($query, $statusid)
    {
        if (! empty($statusid)) {
            $query->where('storageincidencejourneys.statusid', $statusid);
        }
    }

    public static function searchIncidence($folioid)
    {
        return StorageIncidenceJourney::controlAccessUser()
            ->where('storageincidencejourneys.folioid', $folioid)->first();
    }
}
